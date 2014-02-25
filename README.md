# Coin Stats

## What is it?

Keep track of your cryptocoins from multiple services. Keep an eye on your worker stats on different pools. Automatically convert your coins to BTC.

### Supported Services
- Blockchain
  - Balance
  - USD exchange rate
- BTCGuild.com
  - Balance
  - Worker stats
- Coinex
  - Balances
- Cryptsy
  - Balance
  - Exchange rates
  - Autosell (schedule via cron)
- Multipool.us
  - Balances
- ScryptGuild
  - Balances
- WeMineLTC.com
  - Balance

**Demo:** http://scripts.3dgo.net/bitcoin/

**Donate:** 1Ene3hqRejKRkPX1FvHhU24Qi3Wi1fuw52

## Install

1. Create a cache folder the web server can write to
  - By default it expects the cache folder to be directly under the coinstats directory. You can change that by changing line 13 of fetch.php. At some point, I'll make the configuration better and it will be an option there.
2. Rename config.dist.ini.php to config.ini.php
3. Add your information to config.ini.php
4. Profit

If you want to change the order of things, reorder the sections in config.ini

If you want to use the Cryptsy autosell, schedule sell-cli.php using cron. It will not run via a web browser.
