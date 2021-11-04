<x-guest-layout>
    <x-jet-authentication-card>
    <x-slot name="logo">
        <x-jet-authentication-card-logo />
    </x-slot>
    <x-jet-validation-errors class="mb-4" />
    <form method="POST" action="{{ route('tenant.store') }}">
        @csrf

        <div>
            <x-jet-label for="name" value="{{ __('Company Name') }}" />
            <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>
        <div class="mt-4">
            <x-jet-label for="domain" value="{{ __('Domain') }}" />
            <x-jet-input id="domain" class="block mt-1 w-full" type="text" name="domain" :value="old('domain')" required autofocus autocomplete="name" />
            <span id="preview-domain"></span>
        </div>
        <div class="mt-4">
            <x-jet-label for="database" value="{{ __('Database Name') }}" />
            <x-jet-input id="database" class="block mt-1 w-full" type="text" name="database" :value="old('database')" required autofocus autocomplete="database"/>
        </div>
        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mt-4">
                <x-jet-label for="terms">
                    <div class="flex items-center">
                        <x-jet-checkbox name="terms" id="terms" required/>

                        <div class="ml-2">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </div>
                    </div>
                </x-jet-label>
            </div>
        @endif
        <br>
        <x-jet-button class="ml-4">
            {{ __('Register') }}
        </x-jet-button>
    </form>
    </x-jet-authentication-card>

    <script>
        var domain = document.getElementById("domain");

        domain.addEventListener('change', function(){
            if(domain.value != ""){
                document.getElementById("preview-domain").innerHTML = domain.value+".localhost";
            }else{
                document.getElementById("preview-domain").innerHTML = "";
            }
        });
    </script>
</x-guest-layout>