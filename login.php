<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php include 'components/header.php'; ?>

    <main class="flex items-center justify-center min-h-screen py-12 px-4">
        <div class="w-full max-w-md mx-auto">
            <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Welcome Back!</h1>
                    <p class="text-gray-500 mt-2">Sign in to continue to your account.</p>
                </div>

                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <span class="font-medium">Error:</span> erros message will be displayed here.
                </div>

                <form action="#" method="POST" class="space-y-6">
                    <div>
                        <label for="email" class="text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" required
                               class="mt-1 block w-full h-11 px-4 rounded-md border border-gray-300 focus:border-gray-900 transition-colors"
                               placeholder="you@example.com">
                    </div>

                    <div>
                        <div class="flex justify-between items-center">
                            <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-900">Forgot password?</a>
                        </div>
                        <input type="password" id="password" name="password" required
                               class="mt-1 block w-full h-11 px-4 rounded-md border border-gray-300 focus:border-gray-900 transition-colors"
                               placeholder="••••••••">
                    </div>

                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-11 px-4 transition-colors">
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="register.php" class="font-medium text-gray-900 hover:underline">Sign up</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
</html>
