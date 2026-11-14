<x-layouts.auth title="OTP Verification – Mono LMS">
    <div class="space-y-6">
        <!-- Header -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#191919]">{{ ($channel ?? 'email') === 'sms' ? 'SMS' : 'Email' }} OTP Verification</h1>
                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-[#344c3d]/10 text-[#344c3d] border border-[#344c3d]/20">
                    {{ ($role ?? 'student') === 'instructor' ? 'Instructor' : 'Student' }}
                </span>
            </div>
            <p class="text-sm text-[#5d5d5d]">
                We sent a 6-digit verification code to <span class="font-semibold text-[#191919]">{{ $displayTarget ?? ($maskedEmail ?? 'your contact') }}</span>
            </p>
        </div>

        <!-- Status Alerts -->
        @if (session('status'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->has('otp'))
            <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ $errors->first('otp') }}</span>
            </div>
        @endif

        <!-- OTP Verification Form -->
        <form id="otp-form" action="{{ route('otp.verify') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="otp" id="full-otp-input" value="">

            <div class="space-y-4">
                <!-- 6 Digit Inputs -->
                <div class="flex items-center justify-between gap-2 sm:gap-3" id="otp-inputs-container">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               maxlength="1"
                               data-otp-index="{{ $i }}"
                               class="otp-digit w-11 h-14 sm:w-13 sm:h-16 text-center text-xl sm:text-2xl font-bold rounded-xl border border-slate-200 text-slate-900 bg-white focus:outline-none focus:border-[#344c3d] focus:ring-2 focus:ring-[#344c3d]/20 transition-all shadow-xs"
                               autocomplete="one-time-code"
                               {{ $i === 0 ? 'autofocus' : '' }}>
                    @endfor
                </div>

                <!-- Timer Badge -->
                <div class="flex justify-center">
                    <div id="otp-timer-badge" class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-xs font-semibold">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="otp-timer-text">Code expires in <strong id="otp-countdown">10:00</strong></span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="otp-submit-btn" class="w-full bg-[#344c3d] hover:bg-[#25372c] text-white py-3.5 px-6 rounded-xl font-semibold text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                <span>Verify & Complete Registration</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <!-- Resend Section -->
        <div class="pt-2 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#5d5d5d]">
            <span>Didn't receive the verification code?</span>
            <div class="flex items-center gap-2 flex-wrap">
                <form action="{{ route('otp.resend') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="channel" value="{{ $channel ?? 'email' }}">
                    <button type="submit" class="font-semibold text-[#344c3d] hover:underline cursor-pointer">
                        Resend Code
                    </button>
                </form>
                @if (!empty($smsOtpAvailable))
                    <span class="text-slate-300">&bull;</span>
                    <form action="{{ route('otp.resend') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="channel" value="{{ ($channel ?? 'email') === 'sms' ? 'email' : 'sms' }}">
                        <button type="submit" class="font-semibold text-[#344c3d] hover:underline cursor-pointer">
                            Send via {{ ($channel ?? 'email') === 'sms' ? 'Email' : 'SMS' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Back Link -->
        <div class="text-center text-xs text-slate-400">
            <a href="{{ ($role ?? 'student') === 'instructor' ? route('instructor.register') : route('register') }}" class="hover:text-slate-600 transition-colors">
                &larr; Return to registration
            </a>
        </div>
    </div>

    <!-- OTP Input Auto-advance & Countdown Timer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var digits = Array.from(document.querySelectorAll('.otp-digit'));
            var fullInput = document.getElementById('full-otp-input');
            var form = document.getElementById('otp-form');

            function updateFullInput() {
                var code = digits.map(function (d) { return d.value; }).join('');
                fullInput.value = code;
            }

            digits.forEach(function (input, idx) {
                input.addEventListener('input', function (e) {
                    var val = this.value.replace(/\D/g, '');
                    this.value = val ? val.charAt(val.length - 1) : '';

                    updateFullInput();

                    if (this.value && idx < digits.length - 1) {
                        digits[idx + 1].focus();
                        digits[idx + 1].select();
                    }
                });

                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && !this.value && idx > 0) {
                        digits[idx - 1].focus();
                        digits[idx - 1].value = '';
                        updateFullInput();
                    }
                });

                input.addEventListener('paste', function (e) {
                    e.preventDefault();
                    var pasteData = (e.clipboardData || window.clipboardData).getData('text');
                    var clean = pasteData.replace(/\D/g, '').slice(0, 6);
                    if (clean) {
                        clean.split('').forEach(function (char, i) {
                            if (digits[i]) {
                                digits[i].value = char;
                            }
                        });
                        updateFullInput();
                        var nextIdx = Math.min(clean.length, digits.length - 1);
                        digits[nextIdx].focus();
                    }
                });
            });

            form.addEventListener('submit', function (e) {
                updateFullInput();
                if (fullInput.value.length < 6) {
                    e.preventDefault();
                    alert('Please enter all 6 digits of your verification code.');
                    var firstEmpty = digits.find(function (d) { return !d.value; });
                    if (firstEmpty) firstEmpty.focus();
                }
            });

            // Countdown timer
            var remaining = parseInt('{{ $remainingSeconds ?? 600 }}', 10);
            var countdownEl = document.getElementById('otp-countdown');
            var badgeEl = document.getElementById('otp-timer-badge');
            var timerTextEl = document.getElementById('otp-timer-text');

            if (remaining > 0 && countdownEl) {
                var interval = setInterval(function () {
                    remaining--;
                    if (remaining <= 0) {
                        clearInterval(interval);
                        timerTextEl.innerHTML = '<span class="text-red-700">Code expired! Please click Resend Code.</span>';
                        badgeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full text-xs font-semibold';
                        return;
                    }

                    var mins = Math.floor(remaining / 60);
                    var secs = remaining % 60;
                    countdownEl.textContent = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
                }, 1000);
            } else if (remaining <= 0 && timerTextEl) {
                timerTextEl.innerHTML = '<span class="text-red-700">Code expired! Please click Resend Code.</span>';
                badgeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full text-xs font-semibold';
            }
        });
    </script>
</x-layouts.auth>
