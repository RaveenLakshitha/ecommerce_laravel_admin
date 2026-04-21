<!-- Auth Modals Styles -->
<style>
    .auth-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 3000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s var(--ease-out);
    }
    .auth-modal-overlay.open {
        opacity: 1;
        pointer-events: auto;
    }
    
    .auth-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -45%) scale(0.95);
        width: 100%;
        max-width: 480px;
        background: var(--bg-2);
        border: 1px solid var(--bg-4);
        border-top: 3px solid var(--gold);
        z-index: 3001;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s var(--ease-out), transform 0.3s var(--ease-out);
        box-shadow: 0 32px 64px rgba(0, 0, 0, 0.6);
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .auth-modal.open {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
        pointer-events: auto;
    }
    
    .auth-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--bg-4);
    }
    
    .auth-modal-header h3 {
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--white);
        margin: 0;
    }
    
    .auth-modal-close {
        background: none;
        border: none;
        color: var(--silver);
        font-size: 1.5rem;
        cursor: pointer;
        transition: color 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }
    
    .auth-modal-close:hover {
        color: var(--gold);
        transform: rotate(90deg);
    }
    
    .auth-modal-body {
        padding: 2rem;
    }
    
    .auth-form-group {
        margin-bottom: 1.5rem;
    }
    
    .auth-form-group label {
        display: block;
        font-family: var(--font-display);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--silver);
        margin-bottom: 0.5rem;
    }
    
    .auth-input {
        width: 100%;
        background: var(--bg-3);
        border: 1px solid var(--bg-4);
        padding: 0.875rem 1rem;
        font-family: var(--font-body);
        font-size: 0.95rem;
        color: var(--off-white);
        transition: border-color 0.2s, background 0.2s;
        outline: none;
    }
    
    .auth-input:focus {
        border-color: var(--gold);
        background: var(--bg-4);
    }
    
    .auth-input::placeholder {
        color: var(--dim);
    }
    
    .auth-error {
        display: block;
        color: var(--red);
        font-size: 0.8rem;
        margin-top: 0.4rem;
        font-weight: 500;
    }
    
    .auth-btn {
        width: 100%;
        background: var(--gold);
        color: var(--bg);
        border: none;
        padding: 1rem;
        font-family: var(--font-display);
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
        margin-top: 1rem;
    }
    
    .auth-btn:hover {
        background: var(--white);
    }
    
    .auth-btn:active {
        transform: scale(0.98);
    }
    
    .auth-switch {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        color: var(--silver);
    }
    
    .auth-switch a {
        color: var(--gold);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .auth-switch a:hover {
        color: var(--white);
        text-decoration: underline;
    }
</style>

<!-- Auth Modal Overlay -->
<div id="authModalOverlay" class="auth-modal-overlay" onclick="closeAuthModals()"></div>

<!-- Login Modal -->
<div id="loginModal" class="auth-modal">
    <div class="auth-modal-header">
        <h3>{{ __('file.sign_in') }}</h3>
        <button type="button" class="auth-modal-close" onclick="closeAuthModals()">&times;</button>
    </div>
    <div class="auth-modal-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="auth_modal_action" value="login">
            
            <div class="auth-form-group">
                <label for="login_email">{{ __('messages.Email Address') ?? 'Email Address' }}</label>
                <input id="login_email" type="email" name="email" class="auth-input" value="{{ old('auth_modal_action') != 'register' ? old('email') : '' }}" required autofocus placeholder="customer@example.com">
                @if(old('auth_modal_action') != 'register')
                    @error('email') <span class="auth-error">{{ $message }}</span> @enderror
                @endif
            </div>
            
            <div class="auth-form-group">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                    <label for="login_password" style="margin-bottom:0;">{{ __('messages.Password') ?? 'Password' }}</label>
                    <a href="{{ Route::has('password.request') ? route('password.request') : '#' }}" style="font-size:0.75rem; color:var(--dim); text-decoration:none;">Forgot password?</a>
                </div>
                <input id="login_password" type="password" name="password" class="auth-input" required placeholder="********">
                @if(old('auth_modal_action') != 'register')
                    @error('password') <span class="auth-error">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="auth-form-group" style="display:flex; align-items:center; gap:0.5rem;">
                <input type="checkbox" id="remember_me" name="remember" style="accent-color: var(--gold); width:16px; height:16px;">
                <label for="remember_me" style="margin-bottom:0; font-size:0.75rem; font-weight:500; text-transform:none; letter-spacing:0.05em; color:var(--silver);">Remember me</label>
            </div>
            
            <button type="submit" class="auth-btn">{{ __('messages.Sign in to your account') ?? 'Sign In' }}</button>
            
            <div class="auth-switch">
                Don't have an account? <a href="#" onclick="switchAuthModal('register'); return false;">Register Now</a>
            </div>
        </form>
    </div>
</div>

<!-- Register Modal -->
<div id="registerModal" class="auth-modal">
    <div class="auth-modal-header">
        <h3>{{ __('file.register') }}</h3>
        <button type="button" class="auth-modal-close" onclick="closeAuthModals()">&times;</button>
    </div>
    <div class="auth-modal-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="hidden" name="auth_modal_action" value="register">
            
            <div class="auth-form-group">
                <label for="reg_name">{{ __('messages.Full Name') ?? 'Full Name' }}</label>
                <input id="reg_name" type="text" name="name" class="auth-input" value="{{ old('auth_modal_action') == 'register' ? old('name') : '' }}" required placeholder="John Doe">
                @if(old('auth_modal_action') == 'register')
                    @error('name') <span class="auth-error">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="auth-form-group">
                <label for="reg_email">{{ __('messages.Email Address') ?? 'Email Address' }}</label>
                <input id="reg_email" type="email" name="email" class="auth-input" value="{{ old('auth_modal_action') == 'register' ? old('email') : '' }}" required placeholder="customer@example.com">
                @if(old('auth_modal_action') == 'register')
                    @error('email') <span class="auth-error">{{ $message }}</span> @enderror
                @endif
            </div>
            
            <div class="auth-form-group">
                <label for="reg_password">{{ __('messages.Password') ?? 'Password' }}</label>
                <input id="reg_password" type="password" name="password" class="auth-input" required placeholder="********" minlength="8">
                @if(old('auth_modal_action') == 'register')
                    @error('password') <span class="auth-error">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="auth-form-group">
                <label for="reg_password_confirmation">{{ __('messages.Confirm Password') ?? 'Confirm Password' }}</label>
                <input id="reg_password_confirmation" type="password" name="password_confirmation" class="auth-input" required placeholder="********">
            </div>
            
            <button type="submit" class="auth-btn">{{ __('messages.Create Account') ?? 'Create Account' }}</button>
            
            <div class="auth-switch">
                Already have an account? <a href="#" onclick="switchAuthModal('login'); return false;">Sign In</a>
            </div>
        </form>
    </div>
</div>

<script>
    function openAuthModal(type) {
        // close any open modal
        document.querySelectorAll('.auth-modal').forEach(m => m.classList.remove('open'));
        
        let overlay = document.getElementById('authModalOverlay');
        let modal = document.getElementById(type + 'Modal');
        
        if (overlay && modal) {
            overlay.classList.add('open');
            modal.classList.add('open');
            // disable body scroll
            document.body.style.overflow = 'hidden';
            
            // Focus first input
            setTimeout(() => {
                let firstInput = modal.querySelector('input[type="email"], input[type="text"]');
                if (firstInput) firstInput.focus();
            }, 300);
        }
    }

    function closeAuthModals() {
        document.getElementById('authModalOverlay')?.classList.remove('open');
        document.querySelectorAll('.auth-modal').forEach(m => m.classList.remove('open'));
        document.body.style.overflow = '';
    }

    function switchAuthModal(type) {
        openAuthModal(type);
    }

    // Check if there are validation errors returning from form submission
    document.addEventListener('DOMContentLoaded', function() {
        @if(old('auth_modal_action') == 'register' && $errors->any())
            openAuthModal('register');
        @elseif($errors->any() && session()->has('errors'))
            openAuthModal('login');
        @endif
        
        // Escape key to close
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeAuthModals();
            }
        });
    });
</script>
