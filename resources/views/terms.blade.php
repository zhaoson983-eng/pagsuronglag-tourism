@extends('layouts.terms')

@section('title', 'Terms and Conditions')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Terms and Conditions</h1>
                    <p class="text-gray-600">Last updated: {{ date('F j, Y') }}</p>
                </div>
                @if(request()->query('from') === 'profile_setup' || request()->query('from') === 'business_setup' || request()->query('from') === 'business_profile' || request()->query('from') === 'business_profile_update')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    <strong>Profile Setup Complete!</strong> Please review and accept our Terms and Conditions to continue.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <div class="prose prose-lg max-w-none">
                <h2>1. Introduction</h2>
                <p>Welcome to Pagsurong Lagonoy Tourism Platform ("we," "our," or "us"). These Terms and Conditions govern your use of our website and services. By accessing or using our platform, you agree to be bound by these terms.</p>

                <p>Our platform serves as a digital marketplace connecting tourists and travelers with local businesses in Pagsurong Lagonoy, including hotels, resorts, restaurants, and local product vendors. We facilitate viewing of promotions and special offers, product purchases, and provide a platform for reviews and ratings.</p>

                <h2>2. Acceptance of Terms</h2>
                <p>By registering for an account, browsing our platform, making purchases, or using any of our services, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use our platform.</p>

                <h2>3. Description of Services</h2>
                <p>Our platform provides the following services:</p>
                <ul>
                    <li><strong>For Visitors:</strong> Browse and discover local accommodations (hotels, resorts), attractions, and local product vendors in Pagsurong Lagonoy.</li>
                    <li><strong>For Local Businesses:</strong> List and manage business profiles, showcase products and services, and connect with potential customers.</li>
                    <li><strong>Promotional Content:</strong> View promotions, deals, and special offers from hotels, resorts, and attractions in the area.</li>
                    <li><strong>Local Products:</strong> Browse and purchase authentic local products directly from vendors in Pagsurong Lagonoy.</li>
                    <li><strong>For All Users:</strong> Access tourism information, read reviews, and participate in the local community.</li>
                </ul>

                <h2>4. User Accounts and Registration</h2>

                <h3>4.1 Account Creation</h3>
                <p>To use our services, you must create an account by providing accurate, complete, and current information. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>

                <h3>4.2 Account Types</h3>
                <ul>
                    <li><strong>Visitor Accounts:</strong> For individuals browsing and discovering local businesses and attractions.</li>
                    <li><strong>Business Owner Accounts:</strong> For local business owners to list and manage their services and products.</li>
                    <li><strong>Administrator Accounts:</strong> For platform management and moderation.</li>
                </ul>

                <h3>4.3 Verification Process</h3>
                <p>Business owner accounts require verification through submission of business permits and other documentation. We reserve the right to approve or reject business listings based on our verification process.</p>

                <h2>5. User Responsibilities and Conduct</h2>

                <h3>5.1 General Conduct</h3>
                <p>You agree to use our platform only for lawful purposes and in accordance with these Terms. You must not:</p>
                <ul>
                    <li>Use our platform for any illegal or unauthorized purpose</li>
                    <li>Violate any applicable laws or regulations</li>
                    <li>Infringe on intellectual property rights</li>
                    <li>Transmit harmful, offensive, or inappropriate content</li>
                    <li>Attempt to gain unauthorized access to our systems</li>
                    <li>Interfere with other users' use of the platform</li>
                </ul>

                <h3>5.2 Content Standards</h3>
                <p>All content submitted to our platform must:</p>
                <ul>
                    <li>Be accurate and not misleading</li>
                    <li>Not contain harmful, offensive, or inappropriate material</li>
                    <li>Respect the rights of others</li>
                    <li>Comply with applicable laws and regulations</li>
                </ul>

                <h3>5.3 Business Owner Responsibilities</h3>
                <p>Business owners must:</p>
                <ul>
                    <li>Provide accurate information about their business</li>
                    <li>Maintain current product availability and pricing information</li>
                    <li>Respond to customer inquiries in a timely manner</li>
                    <li>Fulfill product orders as agreed</li>
                    <li>Comply with all applicable business regulations</li>
                    <li>Ensure all promotional content is accurate and not misleading</li>
                </ul>

                <h2>6. Privacy and Data Protection</h2>

                <h3>6.1 Data Collection</h3>
                <p>We collect and process personal information in accordance with our Privacy Policy, including:</p>
                <ul>
                    <li>Personal details provided during registration</li>
                    <li>Transaction and purchase information</li>
                    <li>Communication records</li>
                    <li>Usage data and analytics</li>
                    <li>Business documentation for verification purposes</li>
                </ul>

                <h3>6.2 Data Usage</h3>
                <p>We use collected data to:</p>
                <ul>
                    <li>Provide and improve our services</li>
                    <li>Process transactions and purchases</li>
                    <li>Communicate with users</li>
                    <li>Verify business legitimacy</li>
                    <li>Ensure platform security and prevent fraud</li>
                    <li>Comply with legal obligations</li>
                </ul>

                <h3>6.3 Data Sharing</h3>
                <p>We may share data with:</p>
                <ul>
                    <li>Service providers and business partners</li>
                    <li>Legal authorities when required by law</li>
                    <li>Other users as necessary for transactions</li>
                    <li>Third parties with your explicit consent</li>
                </ul>

                <h2>7. Intellectual Property</h2>

                <h3>7.1 Platform Content</h3>
                <p>All content on our platform, including text, graphics, logos, and software, is protected by intellectual property laws and remains our property or that of our licensors.</p>

                <h3>7.2 User Content</h3>
                <p>You retain ownership of content you submit to our platform. By submitting content, you grant us a license to use, display, and distribute such content as necessary to provide our services.</p>

                <h3>7.3 Prohibited Uses</h3>
                <p>You may not reproduce, distribute, or create derivative works from our platform content without our explicit written permission.</p>

                <h2>8. Payment and Transactions</h2>

                <h3>8.1 Payment Processing</h3>
                <p>All payments are processed through secure third-party payment processors. We do not store payment information on our servers.</p>

                <h3>8.2 Pricing and Fees</h3>
                <p>Business owners set their own prices for products and services. We may charge service fees or commissions on transactions as disclosed during the purchase process.</p>

                <h3>8.3 Refunds and Cancellations</h3>
                <p>Refund and cancellation policies are determined by individual business owners. We are not responsible for refunds unless otherwise specified in our policies.</p>

                <h2>9. Reviews and Ratings</h2>

                <h3>9.1 Review Guidelines</h3>
                <p>Users may submit reviews and ratings based on their genuine experiences. Reviews must be honest, factual, and respectful.</p>

                <h3>9.2 Review Moderation</h3>
                <p>We reserve the right to moderate, edit, or remove reviews that violate our content standards or appear to be fraudulent.</p>

                <h3>9.3 Business Responses</h3>
                <p>Business owners may respond to reviews professionally and factually. All responses are public and must comply with our content standards.</p>

                <h2>10. Platform Availability and Service Levels</h2>
                <p>While we strive to maintain continuous platform availability, we do not guarantee uninterrupted service. We may perform maintenance, updates, or experience technical difficulties that temporarily affect service availability.</p>

                <h2>11. Limitation of Liability</h2>

                <h3>11.1 General Disclaimer</h3>
                <p>Our platform is provided "as is" without warranties of any kind. We disclaim all warranties, express or implied, including merchantability and fitness for a particular purpose.</p>

                <h3>11.2 Limitation of Damages</h3>
                <p>To the maximum extent permitted by law, we shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of our platform.</p>

                <h3>11.3 User Responsibility</h3>
                <p>Users are responsible for verifying information before making transactions. We are not liable for damages resulting from reliance on platform content.</p>

                <h2>12. Indemnification</h2>
                <p>You agree to indemnify and hold us harmless from any claims, damages, losses, or expenses arising from your use of our platform, violation of these terms, or infringement of any rights of another party.</p>

                <h2>13. Termination</h2>

                <h3>13.1 Account Termination</h3>
                <p>We reserve the right to suspend or terminate your account at our discretion for violations of these terms or other inappropriate conduct.</p>

                <h3>13.2 Data Retention</h3>
                <p>Upon account termination, your data may be retained as required by law or for legitimate business purposes.</p>

                <h2>14. Dispute Resolution</h2>

                <h3>14.1 Governing Law</h3>
                <p>These terms are governed by the laws of the Republic of the Philippines.</p>

                <h3>14.2 Dispute Resolution Process</h3>
                <p>Disputes should first be resolved through direct communication. If resolution cannot be reached, disputes may be subject to mediation or arbitration as appropriate.</p>

                <h2>15. Changes to Terms</h2>
                <p>We reserve the right to modify these terms at any time. We will notify users of significant changes through platform announcements or email. Continued use of our platform after changes constitutes acceptance of the new terms.</p>

                <h2>16. Severability</h2>
                <p>If any provision of these terms is found to be unenforceable, the remaining provisions shall remain in full force and effect.</p>

                <h2>17. Contact Information</h2>
                <p>If you have questions about these Terms and Conditions, please contact us:</p>
                <ul>
                    <li><strong>Email:</strong> legal@pagsuronglagonoy.com</li>
                    <li><strong>Address:</strong> Pagsurong Lagonoy Tourism Office, Lagonoy, Camarines Sur, Philippines</li>
                    <li><strong>Phone:</strong> [Contact Number]</li>
                </ul>

                <h2>18. Acknowledgment</h2>
                <p>By using our platform, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.</p>

                <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Important:</strong> These Terms and Conditions are effective as of {{ date('F j, Y') }}. Please review them periodically for updates.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if(request()->query('from') === 'profile_setup' || request()->query('from') === 'business_setup' || request()->query('from') === 'business_profile' || request()->query('from') === 'business_profile_update')
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <form method="POST" action="{{ route('terms.accept') }}" class="inline">
                        @csrf
                        <input type="hidden" name="from" value="{{ request()->query('from') }}">
                        <input type="hidden" name="redirect_route" value="{{ $redirectRoute ?? 'home' }}">
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-8 rounded-lg transition-colors duration-200 flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Accept Terms & Continue
                        </button>
                    </form>

                    <form method="POST" action="{{ route('terms.decline') }}" class="inline">
                        @csrf
                        <input type="hidden" name="from" value="{{ request()->query('from') }}">
                        <input type="hidden" name="redirect_route" value="{{ $redirectRoute ?? 'home' }}">
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-8 rounded-lg transition-colors duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Decline Terms
                        </button>
                    </form>
                </div>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        By accepting these terms, you agree to be bound by our Terms and Conditions and Privacy Policy.
                    </p>
                </div>
            </div>
        @else
            <!-- Regular footer for non-setup users -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Home
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center mb-4">
                    <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="w-8 h-auto mr-3">
                    <div class="font-playfair text-xl font-bold">Pagsurong Lagonoy</div>
                </div>
                <p class="text-gray-300 text-sm mb-4">
                    Your gateway to authentic Camarines Sur tourism experiences. Connect with local businesses and discover the beauty of Pagsurong Lagonoy.
                </p>
                <div class="flex space-x-4">
                    <a href="https://facebook.com" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="https://instagram.com" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="https://twitter.com" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="font-semibold text-white mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors text-sm">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors text-sm">
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors text-sm">
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="text-gray-300 hover:text-white transition-colors text-sm">
                            Terms & Conditions
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="font-semibold text-white mb-4">Support</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">
                            Help Center
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">
                            Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">
                            Report Issue
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-400 text-sm">
                © {{ date('Y') }} Pagsurong Lagonoy Tourism Platform. All rights reserved.
            </div>
            <div class="text-gray-400 text-sm mt-4 md:mt-0">
                Made with ❤️ for the people of Camarines Sur
            </div>
        </div>
    </div>
</footer>

@push('scripts')
<script>
    // Smooth scroll to top when page loads
    document.addEventListener('DOMContentLoaded', function() {
        window.scrollTo(0, 0);
    });

    // Add confirmation for decline action
    document.querySelector('button[type="submit"][form*="decline"]').addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to decline the Terms and Conditions? You will not be able to use our platform without accepting them.')) {
            e.preventDefault();
        }
    });
</script>
@endpush
@endsection
