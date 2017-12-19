# Web2All taskmanager

This `web2all/taskmanager` package requires the `web2all/framework` ([https://github.com/web2all/framework](https://github.com/web2all/framework)). It used to be proprietary software but now it has been released to the public domain under a MIT license.

This pacckage is no longer actively maintained. Most likely it is only of interest if you own software created by Web2All B.V. which was built using this taskmanager.

## What does it do ##

This package provides a framework for managing and executing *tasks*.
A task is a command which is scheduled for execution. For example the linux cron does something similar. In fact these classes have been written to abstract the cron behaviour and to allow custom implementations, as an alternative to cron.

There are two main components which must be implemented:

- Scheduler
- Storage

For each component an implementation is included in this package. The sheduler is implemented using  `peppeocchi/php-cron-scheduler` (see `Web2All_Task_Scheduler_PhpCronScheduler`). The Storage implementation `Web2All_Task_Storage_CronFile` reads a file in 'cron format'. 

## License ##

Web2All framework is open-sourced software licensed under the MIT license ([https://opensource.org/licenses/MIT](https://opensource.org/licenses/MIT "license")).
