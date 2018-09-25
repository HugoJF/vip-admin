# VIP-Admin - A generic CS:GO VIP processor

VIP-Admin is a PHP-based system to automate multiple tasks when dealing with VIP slots on multiple CS:GO servers. Some of the features that can be found currently implemented are:
- Automatic server synchronization via FTP (`admins_simple.ini` file is updated automatically on every registered server).
- Automatic Steam trading for VIP slots bought with Steam items.
- MercadoPago payment processing for VIP slots bought with real money.
- Token generation for VIP slot giveaway and manual processing (including expiration time, custom durations, etc).
- Multi-server synchronization (you can have as many servers as you want being synchronized with the same list).
- Email notifications (when a user creates an order, registers, etc).
- Extra tokens for orders above a certain minimum period (long duration VIP slot owners can gift friends with extra tokens as a trial)
- Communication with Steam is handled in the backend via a NodeJS script that can be completely isolated from the end user.
- Complete English and Brazilian-Portuguese translations.
- Easy to add new payment processors (priority #1 since the project started).
- Steam authentication.

## Why
This system allowed me to focus on improving my game-servers instead of trying to raise funds (via VIP slots) to keep it online. It also allowed near instant and flawless payment processing for any user wanting to support my servers.

## Installation
No special installation instructions are provided since this project is no longer supported. A generic Laravel installation guide should be enough.

## Used in this project
- PHP with Laravel framework.
- Node.JS with Express (daemon that communicates with Steam)
- MercadoLivre API (that provides terrible documentation)
- Steam Node.JS libraries (Steam itself has multiple problems when dealing with item Trade Offers)
- Sentry.IO (error tracking and reporting)
- Laravel Dusk (extensive testing on critical parts of the code)
- Travis CI (running tests when new code is pushed to GitHub)

## Screenshots (from Admin account)

### Steam items selection screen
![Item selection](https://i.imgur.com/YmNNpre.png)

### MercadoPago processing screen
![MercadoPago](https://i.imgur.com/RpOHr0k.png)

### Order list
![Order list](https://i.imgur.com/8DOjhpa.png)

### Confirmation list (VIP slots that were activated)
![List of confirmations](https://i.imgur.com/VTcRAlJ.png)
