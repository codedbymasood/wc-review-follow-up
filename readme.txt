=== Review Follow-Up for WooCommerce ===
Tags: woocommerce, reviews, email automation, social proof, customer feedback
Requires at least: 6.6
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-3.0+
License URI: https://www.gnu.org/licenses/gpl-3.0.txt

Automate review requests with smart timing, follow-ups, and coupon incentives. Build social proof and drive repeat sales.

== Description ==

= Why Choose Review Follow-Up? =

**Build Social Proof** – Generate authentic reviews systematically instead of hoping customers remember to leave feedback on their own.
**Increase Conversion Rates** – Display real customer experiences that convince hesitant shoppers to complete their purchases.
**Reward & Motivate** – Offer exclusive discount codes to reviewers, turning feedback into repeat business opportunities.
**Smart Targeting** – Send requests only to qualified customers after minimum purchase amounts and optimal timing delays.

= How It Works =

1. **Customer completes purchase** – Order is tracked automatically by the plugin
2. **Smart delay trigger** – Review request is scheduled after your configured waiting period
3. **Personalized email sent** – Customer receives a branded review request at the perfect moment
4. **Follow-up reminders** – Non-respondents get additional nudges at customizable intervals
5. **Review approved** – Once admin approves the review, verified purchaser receives exclusive discount code

= Features =

- **Automated review requests** – Send requests after customizable delays (X days post-purchase)
- **Strategic follow-up sequences** – Multiple automated reminder emails to non-respondents
- **Verified purchaser rewards** – Discount codes sent only after reviews are approved to prevent spam
- **Reward limits** – Set maximum number of rewards per user per year to control costs
- **Product-level targeting** – Request reviews for individual items or all purchased products in one email
- **Order-level requests** – Send streamlined review requests covering entire orders
- **Minimum order filters** – Only request reviews from customers exceeding specified purchase amounts
- **Category exclusions** – Exclude entire categories from review campaigns
- **Advanced template customization** – Design compelling, branded email templates with professional design options
- **Dynamic mail tags** – Build personalized emails using multiple variables without touching code
- **Reliable CRON scheduling** – Background monitoring runs independently of site traffic
- **Comprehensive email logs** – Track all review request activity with detailed delivery records

== Frequently Asked Questions ==

= How soon after purchase should I send review requests? =

The optimal timing is typically 7-14 days after delivery, allowing customers time to experience the product. You can customize this delay in the plugin settings to match your product type and customer journey.

= Can I send multiple reminder emails? =

Yes. Configure multiple follow-up emails with custom timing intervals (e.g., 3 days, 7 days later) to non-respondents, dramatically increasing your review collection rate.

= How do discount codes work? =

Discount codes are automatically generated and sent to customers only after their review has been approved by the store admin. This ensures only verified purchasers receive rewards and prevents spam or fake reviews.

= Can customers abuse the discount code system? =

No. You can set a maximum limit for the number of rewards each user can receive per year, preventing abuse while still encouraging genuine feedback.

= Can I exclude certain products from review requests? =

Currently, you can exclude entire categories from review campaigns. Individual product exclusions are not available in this version.

= What's the difference between product-level and order-level requests? =

Product-level requests ask customers to review individual items (with the option to include all purchased products in one email). Order-level requests send a single streamlined message covering the entire order experience.

= How does the minimum order filter work? =

Set a minimum purchase amount threshold to only request reviews from customers who exceed it. This ensures you target invested buyers most likely to leave detailed, valuable feedback.

= Can I customize the email templates? =

Yes. The plugin provides advanced template customization with professional design options and dynamic mail tags (customer names, product details, order info) to create personalized, branded emails without coding.

= What are mail tags? =

Mail tags are dynamic placeholders like {customer_name}, {product_name}, and {order_number} that automatically populate with real customer data, allowing you to create personalized emails easily.

= Will review requests be sent even during low-traffic periods? =

Yes. The plugin uses WordPress CRON scheduling to monitor and send emails independently of site traffic, ensuring consistent delivery on schedule.

= Can I track which emails were sent? =

Absolutely. Comprehensive email logs provide detailed delivery records, response status, and complete transparency for measuring campaign effectiveness.

= Does it work with variable products? =

Yes. The plugin fully supports both simple and variable WooCommerce products, including different sizes, colors, and variations.

= What email services does it support? =

It works with any WordPress email setup including WordPress default mail and SMTP plugins (WP Mail SMTP, Easy WP SMTP, etc.).

= Will it slow down my site? =

No. The plugin is lightweight and optimized for performance. Email sending is handled in the background using scheduled tasks.

= Is there a limit to review requests? =

No limits on review requests. However, you can set limits on how many discount rewards each customer can receive per year to manage costs and prevent abuse.

= How do I prevent spamming customers? =

The plugin includes smart filters: minimum order amounts, category exclusions, and configurable timing delays ensure customers only receive relevant requests at appropriate intervals.

= How do I get support? =

Visit our support page or contact us directly. We typically respond within 24 hours on business days.

== Installation ==

= Minimum Requirements =

* PHP 7.4 or greater is required (PHP 8.0 or greater is recommended)
* MySQL 5.5.5 or greater, OR MariaDB version 10.1 or greater, is required
* WordPress 6.6 or greater
* WooCommerce 8.0 or greater is required

= Automatic installation =

Automatic installation is the easiest option — WordPress will handle the file transfer, and you won't need to leave your web browser. To do an automatic install of Review Follow-Up for WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu, and click "Add New."

In the search field type "Review Follow-Up for WooCommerce," then click "Search Plugins." Once you've found the plugin, you can view details and install it by clicking "Install Now," and WordPress will take it from there.

= Manual installation =

Manual installation requires downloading the plugin and uploading it to your web server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation).

= Updating =

Automatic updates should work smoothly, but we still recommend you back up your site.

If you encounter issues after an update, flush the permalinks by going to WordPress > Settings > Permalinks and hitting "Save." That should return things to normal.

== Screenshots ==

1. General settings panel where you configure review request timing and targeting rules.
2. Email template customization screen with design options and mail tag reference.
3. Review request logs showing sent emails, delivery status, and customer responses.
4. Category exclusion settings to control which product categories receive review requests.
5. Coupon incentive configuration for rewarding verified reviewers with discount codes.
6. Reward limit settings to prevent abuse and manage discount code distribution.
7. Follow-up sequence settings to maximize review collection rates.
8. CRON scheduler dashboard monitoring automated email sending.

== Changelog ==

= 1.0.0 2025-10-31 =
* Initial Release
* Add - Automated review requests with customizable post-purchase delays.
* Add - Strategic follow-up sequences for non-respondents.
* Add - Unique coupon incentives sent only after review approval.
* Add - Verified purchaser validation to prevent spam and fake reviews.
* Add - Reward limit controls per user per year.
* Add - Product-level and order-level review targeting options.
* Add - Minimum order amount filters for smart customer targeting.
* Add - Category exclusion controls for review campaigns.
* Add - Advanced email template customization with design options.
* Add - Dynamic mail tags for personalized email content.
* Add - WordPress CRON scheduling for reliable email delivery.
* Add - Comprehensive email logs with delivery tracking.