Travis linting status: ![Travis Built Status Image](https://travis-ci.org/archi/wzet.svg?branch=master)

This is our "Who pays the bill" [German: Wer Zahlt EigenTlich => WZET] tool.

Visuals and code are both very crude, since I put my emphasis on simplicity of development. The benefit is, unlike more modern SPA approaches, adding a feature requires comprehension of a more limited amount of code (or the same amount of code in only a single file).

To get a running instance:

1. Create `inc/config.php` (you can copy and adapt `inc/config.dist.php`).
2. Copy `inc/db/empty.sqlite` to your specified DB.
3. Register a user and set his flag to _1_ [=Admin] in the database.
4. (optional) add phpsqliteadmin to sqladmin.php

General todo (not in any order):

1.  Prevent JS injection via Name/Login (display is not filtered atm); fix German special chars (Umlaute) while at that.
2.  setup.php?
3.  Users shoud be (opt-out) notified when they have been included in an event
4.  A design
6.  Function: Start event so people can RSVP and add their +X
7.  Function: Rollback
8.  Function: Add another bill (by someone else) - DB should be able to reflect this, but the code does not
9.  DB names should be English, not German
10. Display more details in the log
11. Implement communication with cron job (e.g. file with timestamp of last event creation) so overview mails can be sent e.g. with any db-change happening between Monday and Wednesday

DB layout is not quite 100% ERM, ~~but I believe the way I did it now is a bit more versatile.~~
1. I.e. the Users.Konto value could be computed on the fly, but that way it can be bootstrapped with values != 0. This is still good.
2. The list of attendees and their +X in a textual list was a bad idea and needs to be ditched (luckily, that's easy)
