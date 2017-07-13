BadgeSystem
========================

Technologies & versions:
- Symfony 3.3.3

Content
========================
- Create a system of trophies / badges on Symfony 3. We will use the event subscriber system to trigger and unlocking of badge

Install
========================
- Clone project
- Make composer install
- Make php app/console doctrine:database:create
- Make php app/console doctrine:schema:update --force
- Make php app/console doctrine:fixtures:load
- Get http://yourdomain.local/app_dev.php/login