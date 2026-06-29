<div class="flex flex-col items-center">
    <div class="card w-full max-w-md bg-base-100 shadow-xl mb-16">
        <div class="card-body">
            <h2 class="text-center text-xl font-bold mb-4">Login</h2>

            <form method="POST" action="{{ route('login.perform') }}" class="space-y-4">
                @csrf

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nama / Email</span>
                    </label>
                    <input type="text" name="name_or_email" required autofocus class="input input-bordered w-full">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" name="password" required class="input input-bordered w-full">
                </div>

                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-2">
                        <input type="checkbox" name="remember" class="checkbox checkbox-success">
                        <span class="label-text">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    Login
                </button>
            </form>

            <div class="divider">atau</div>

            <a href="{{ route('public.registration') }}" class="btn btn-neutral w-full">
                Register
            </a>
        </div>
    </div>
</div>
