<?php

namespace Laravel\Ui;

class AuthRouteMethods
{
    /**
     * Register the typical authentication routes for an application.
     *
     * @param  array  $options
     * @return callable
     */
    public function auth()
    {
        return function ($options = []) {
            $namespace = class_exists($this->prependGroupNamespace('Auth\LoginController')) ? null : 'App\Http\Controllers';

            $this->group(['namespace' => $namespace], function() use($options) {
                // Login Routes...
                if ($options['login'] ?? true) {
                    $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
                    $this->post('login', 'Auth\LoginController@login');
                }

                // Logout Routes...
                if ($options['logout'] ?? true) {
                    $this->post('logout', 'Auth\LoginController@logout')->name('logout');
                }

                // Registration Routes...
                if ($options['register'] ?? true) {
                    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
                    $this->post('register', 'Auth\RegisterController@register');
                }

                // Password Reset Routes...
                if ($options['reset'] ?? true) {
                    $this->resetPassword();
                }

                // Password Confirmation Routes...
                if ($options['confirm'] ??
                    class_exists($this->prependGroupNamespace('Auth\ConfirmPasswordController'))) {
                    $this->confirmPassword();
                }

                // Email Verification Routes...
                if ($options['verify'] ?? false) {
                    $this->emailVerification();
                }
            });
        };
    }

    /**
     * Register the typical reset password routes for an application.
     *
     * @return callable
     */
    public function resetPassword()
    {
        return function () {
            $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
            $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
            $this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
        };
    }

    /**
     * Register the typical confirm password routes for an application.
     *
     * @return callable
     */
    public function confirmPassword()
    {
        return function () {
            $this->get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
            $this->post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
        };
    }

    /**
     * Register the typical email verification routes for an application.
     *
     * @return callable
     */
    public function emailVerification()
    {
        return function () {
            $this->get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
            $this->get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
            $this->post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
        };
    }
}
