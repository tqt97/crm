<?php

namespace App\Services\Users;

use App\Models\User;
use App\Enums\UserType;
use App\Enums\TokenAbility;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Users\UserRepositoryInterface;

class UserService
{
    const TOKEN_NAME = 'thk-token';

    private $userRepository;
    private $userProfileRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Login User
     *
     * Return Token || false
     */
    public function actionLogin($request)
    {
        $isAdmin = json_decode($request->get('is_admin')) ?? false;
        $results = [
            'success' => true,
            'message' => __('login successful'),
            'code' => Response::HTTP_OK,
        ];
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if ($isAdmin) {
            $credentials['type'] = UserType::ADMIN->value;
        }

        $isExits = $this->userRepository->checkUserExists($request);
        if (!$isExits) {
            $results = [
                'success' => false,
                'message' => __('Unauthorized'),
                'code' => Response::HTTP_UNAUTHORIZED,
            ];
            return $results;
        }

        if (!Auth::attempt($credentials, $request->remember ?? false)) {
            if ($isAdmin) {
                $results = [
                    'success' => false,
                    'message' => __('Forbidden'),
                    'code' => Response::HTTP_FORBIDDEN,
                ];
            }
            return $results;
        }

        $user = $request->user();
        $ability = $isAdmin ? TokenAbility::ADMIN_ABILITY->value : TokenAbility::EMPLOYEE_ABILITY->value;
        $token = $user->createToken(self::TOKEN_NAME, [$ability])->plainTextToken;
        $results['token'] = $token;
        return $results;
    }

    /**
     * Register new user
     *
     * Return Token
     */
    public function registerNewUser($request)
    {
        $user = $this->userRepository->create($request);
        if ($user) {
            $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
            return $token;
        }
        return false;
    }

    /**
     * Get List User with Pagination
     *
     * @param mixed $request
     * @return mixed
     */
    public function getListUser($request): mixed
    {
        return $this->userRepository->getListWithPagination($request);
    }

    /**
     * Create a User
     *
     * @param mixed $request
     * @return mixed
     */
    public function createUserWithProfile($request): mixed
    {
        $user = $this->userRepository->create($request);
        if (!$user) {
            return false;
        }
        $positions = $request['positions'] ?? [];
        foreach ($positions as $position) {
            $departmentId = $position['department_id'];
            $positionIds = $position['position_ids'];

            $user->positions()->attach($positionIds, ['department_id' => $departmentId]);
        }
        return $user;
    }

    /**
     * Update a User
     *
     * @param mixed $uuid
     * @param mixed $request
     * @return mixed
     */
    public function updateUserWithProfile($uuid, $request): mixed
    {
        $user = $this->userRepository->findByUuidWithRelation($uuid, []);
        if (!$user) {
            return false;
        }

        $positions = $request['positions'] ?? [];
        $dataSync = [];
        if (empty($positions)) {
            $user->positions()->sync($positions);
        } else {
            foreach ($positions as $position) {
                $departmentId = $position['department_id'];
                $positionIds = $position['position_ids'];
                foreach ($positionIds as $positionId) {
                    $dataSync[$positionId]=['department_id' => $departmentId];
                }
            }
        }
        $user->positions()->sync($dataSync);

        return $user;
    }

    /**
     * Get a User
     *
     * @param mixed $uuid
     * @return mixed
     */
    public function getUserByUuid($uuid): mixed
    {
        $userProfile = $this->userRepository->findByUuid($uuid);

        return $userProfile;
    }

    /**
     * Delete a User
     *
     * @param mixed $uuid
     * @return mixed
     */
    public function deleteUserByUuid($uuid): mixed
    {
        $user = $this->userRepository->findByUuid($uuid);
        if ($user) {
            // delete User
            $user->delete();
            // delete Profile
            $user->profile->delete();
            return true;
        }
        return false;
    }

    /**
     * Resend Password
     *
     * Return Token
     */
    public function forgotPassword($request)
    {
        $passwordReset = PasswordResetToken::createOrFirst(['email' => $request->email], ['token' => Str::random(32)]);
        if ($passwordReset->wasRecentlyCreated) {
            return true;
        } else {
            $throttleToken = config('erp.throttle_token');
            if (now()->diffInMinutes($passwordReset->updated_at, true) < $throttleToken) {
                return false;
            }
            $passwordReset = $passwordReset->update(['token' => Str::random(32)]);
        }

        return $passwordReset;
    }

    /**
     * Reset Password
     */
    public function resetPassword($request)
    {
        $passwordReset = PasswordResetToken::where([
            'token' => $request->token,
        ])->first();
        if (!$passwordReset) {
            return false;
        }
        $expiryMinutes = config('auth.passwords.users.expire');
        if (now()->diffInMinutes($passwordReset->updated_at, true) > $expiryMinutes) {
            return false;
        }
        User::where('email', $passwordReset->email)->first()->update($request->only('password'));
        $passwordResetToken = $passwordReset->delete();
        if ($passwordResetToken) {
            return true;
        }

        return false;
    }
}
