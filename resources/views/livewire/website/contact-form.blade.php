<div>
    {{-- resources/views/livewire/contact-form.blade.php --}}
    <div class="call-back-form">

        <!-- Success Message -->
        @if ($showSuccess)
            <div class="alert alert-success alert-dismissible" style="margin-bottom: 30px;">
                <button type="button" class="close" wire:click="closeSuccess">&times;</button>
                <i class="fa fa-check-circle"></i>
                {{ __('messages.contact_form.success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if ($showError)
            <div class="alert alert-danger alert-dismissible" style="margin-bottom: 30px;">
                <button type="button" class="close" wire:click="closeError">&times;</button>
                <i class="fa fa-exclamation-circle"></i>
                {{ $errorMessage }}
            </div>
        @endif

        <!-- Call Back Form -->
        <form wire:submit.prevent="submit">
            <div class="row clearfix">

                <!-- Name Field -->
                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                    <input type="text" wire:model.blur="name" placeholder="{{ __('messages.contact_form.name') }}"
                        class="@error('name') error @enderror" required>
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                    <input type="email" wire:model.blur="email" placeholder="{{ __('messages.contact_form.email') }}"
                        class="@error('email') error @enderror" required>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone Field -->
                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                    <input type="text" wire:model.blur="phone" placeholder="{{ __('messages.contact_form.phone') }}"
                        class="@error('phone') error @enderror" required>
                    @error('phone')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Subject Field -->
                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                    <input type="text" wire:model.blur="subject"
                        placeholder="{{ __('messages.contact_form.subject') }}"
                        class="@error('subject') error @enderror" required>
                    @error('subject')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Service Type Field -->
                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                    <select wire:model.blur="service_type"
                        class="custom-select-box @error('service_type') error @enderror">
                        <option value="">{{ __('messages.contact_form.service_type') }}</option>
                        <option value="engineering">{{ __('messages.contact_form.service_options.engineering') }}
                        </option>
                        <option value="maintenance">{{ __('messages.contact_form.service_options.maintenance') }}
                        </option>
                        <option value="technology">{{ __('messages.contact_form.service_options.technology') }}
                        </option>
                        <option value="spare_parts">{{ __('messages.contact_form.service_options.spare_parts') }}
                        </option>
                        <option value="consultation">{{ __('messages.contact_form.service_options.consultation') }}
                        </option>
                        <option value="other">{{ __('messages.contact_form.service_options.other') }}</option>
                    </select>
                    @error('service_type')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                    <button class="theme-btn submit-btn" type="submit" name="submit-form" wire:loading.attr="disabled"
                        @if ($isSubmitting) disabled @endif>

                        <span wire:loading.remove wire:target="submit">
                            {{ __('messages.contact_form.submit') }}
                        </span>

                        <span wire:loading wire:target="submit">
                            <i class="fa fa-spinner fa-spin"></i>
                            {{ __('messages.common.loading') }}
                        </span>
                    </button>
                </div>

            </div>
        </form>
    </div>

    <style>
        /* Custom styles for form validation */
        .form-group input.error,
        .form-group select.error {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .error-text {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            position: relative;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert .close {
            position: absolute;
            top: 8px;
            right: 15px;
            color: inherit;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.7;
        }

        .alert .close:hover {
            opacity: 1;
        }

        .alert i {
            margin-right: 10px;
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .fa-spin {
            animation: fa-spin 2s infinite linear;
        }

        @keyframes fa-spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</div>
