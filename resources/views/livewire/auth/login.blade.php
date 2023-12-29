<div class="col-lg-8">
    <div class="card-group d-block d-md-flex row">
        <div class="card col-md-7 p-4 mb-0">
            <form wire:submit.prevent="authenticate" class="card-body">
                <h1>Login</h1>
                <p class="text-medium-emphasis">Sign In to your account</p>
                <div class="form-group mb-3">
                    <div class="input-group">
                    <span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                      </svg></span>
                        <input class="form-control" type="text" placeholder="Username" wire:model="email">
                    </div>
                    @error('email')<div class="text-danger d-block">{{ $message }}</div>@enderror
                </div>
                <div class="form-group  mb-4">
                    <div class="input-group"><span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                      </svg></span>
                        <input class="form-control" type="password" placeholder="Password" wire:model="password">
                    </div>
                    @error('password')<div class="text-danger d-block">{{ $message }}</div>@enderror
                </div>
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-primary px-4" type="submit">Login</button>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('password.email') }}" wire:navigation class="btn btn-link px-0">Forgot password?</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card col-md-5 text-white bg-primary py-5">
            <div class="card-body text-center">
                <div>
                    <h2>Sign up</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <button class="btn btn-lg btn-outline-light mt-3" type="button">Register Now!</button>
                </div>
            </div>
        </div>
    </div>
</div>
