<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">
    <?php include 'components/header.php'; ?>

    <main class="flex items-center justify-center min-h-screen py-2 px-2">
        <div class="w-full max-w-md mx-auto">
            <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Create an Account</h1>
                    <p class="text-gray-500 mt-2">Join HomeHaven today!</p>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        <span class="font-medium">Error:</span> <?= $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        <span class="font-medium">Success:</span> <?= $_SESSION['success']; ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <form action="register_user.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div>
                        <label for="fullname" class="text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="fullname" name="fullname" required
                            class="mt-1 block w-full h-11 px-4 rounded-md border border-gray-300 focus:border-gray-900 transition-colors"
                            placeholder="John Doe">
                    </div>

                    <div>
                        <label for="phone" class="text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" id="phone" name="phone" required maxlength="10"
                            class="mt-1 block w-full h-11 px-4 rounded-md border border-gray-300 focus:border-gray-900 transition-colors"
                            placeholder="9876543210">
                    </div>

                    <div>
                        <label for="email" class="text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" required
                            class="mt-1 block w-full h-11 px-4 rounded-md border border-gray-300 focus:border-gray-900 transition-colors"
                            placeholder="you@example.com">
                    </div>

                    <div>
                        <label for="avatar" class="text-sm font-medium text-gray-700">Avatar (optional)</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*"
                            class="mt-1 block w-full h-11 px-4 rounded-md border border-gray-300 focus:border-gray-900 transition-colors">
                    </div>

                    <div>
                        <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 block w-full h-11 px-4 rounded-md border border-gray-300 focus:border-gray-900 transition-colors"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <label for="confirm-password" class="text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" required
                            class="mt-1 block w-full h-11 px-4 rounded-md border border-gray-300 focus:border-gray-900 transition-colors"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-11 px-4 transition-colors">
                            Create Account
                        </button>
                    </div>
                </form>


                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="login.php" class="font-medium text-gray-900 hover:underline">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
<script>
<?php
// If email was queued, connect to Node SSE endpoint
if (isset($_SESSION['emailId'])) {
    $emailId = $_SESSION['emailId'];
    unset($_SESSION['emailId']); // clear it after use
    echo "
    const emailId = '$emailId';
    const statusBox = document.createElement('div');
    statusBox.className = 'mt-6 p-4 text-sm bg-blue-50 text-blue-800 rounded-lg border border-blue-200';
    statusBox.textContent = '📬 Welcome email queued... waiting for updates.';
    document.querySelector('main').appendChild(statusBox);

    const evtSource = new EventSource('https://smtp-service-server.vercel.app/api/email/events/$emailId');

    evtSource.onmessage = (event) => {
        const data = JSON.parse(event.data);
        console.log('📡 Email update:', data);x

        if (data.status === 'sending') {
            statusBox.textContent = '🕐 Sending your welcome email...';
        } else if (data.status === 'sent') {
            statusBox.textContent = '✅ Welcome email sent successfully!';
            statusBox.classList.remove('bg-blue-50', 'text-blue-800');
            statusBox.classList.add('bg-green-50', 'text-green-800', 'border-green-200');
            evtSource.close();
        } else if (data.status === 'failed') {
            statusBox.textContent = '❌ Failed to send welcome email: ' + (data.error || 'Unknown error');
            statusBox.classList.remove('bg-blue-50', 'text-blue-800');
            statusBox.classList.add('bg-red-50', 'text-red-800', 'border-red-200');
            evtSource.close();
        }
    };

    evtSource.onerror = () => {
        statusBox.textContent = '⚠️ Connection lost. Please refresh to check email status.';
        evtSource.close();
    };
    ";
}
?>
</script>


</html>