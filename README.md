# Running this REST API
1. Composer install 
2. Create database table_name;
3. php artisan migrate 
4. php artisan db:seed --class=UsersTableSeeder

For Unit Testing
1> ./vendor/bin/codecept run api UserAuthTestCest --steps
2> ./vendor/bin/codecept run api ChecklistTestCest --steps
3> ./vendor/bin/codecept run api HistoriesTestCest --steps
4> ./vendor/bin/codecept run api TemplateTestCest --steps
5> ./vendor/bin/codecept run api ItemsTestCest --steps


## Contributing
This code has been coded by Ahadian Akbar
