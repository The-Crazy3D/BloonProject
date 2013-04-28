# BloonCrypto

BloonCrypto is one of the three project from BloonProject.
BloonCrypto is the world #1 Habbo Hotel Emulator to be in PHP.
BloonCrypto support a R63 Post-Shuffle Release.
BloonCrypto start at 6.0MB (used memory), auto-optimize SQL.

![BloonCrypto](http://i.imgur.com/aW9PZSl.png "BloonCrypto")

## Requierments

NO MORE REQUIERMENTS, when you download the zipsource, a php configuration is auto-installed and ready for use.

### Revision 27350
Just a WARNING : All modifications, all new features, all enhancement are from the Revision 24100 to Revision 27350
This revision is a 'pre-beta' revision.

* Fixed pathfinding sit bug
* Started stacking item and fixed minor bug
* Added pre-configured PHP 5.4 (with all needed extension and optimised (php.ini)
* Fixed minor bug of purchase catalog
* Coded PickupItem handler, finished PurchaseCatalogItem handler, fixed minor bug of OpenCatalogPage handler
* Updated default conf file
* Enhanced pooling class
* Started PDO pooling system
* Now pdo is persistent connexion
* Fixed GetTileFurni function (thanks to Quackster for help)
* Coded command for reload inventory, coded sitting on chair furni
* Enhanced server.php, packet structure
* Added :teleport, :disconnect and enhanced pathfinder (unfinished)
* Fixed change look bug
* Fixed items on loading room, coded :control and :disconnect
* Updating conf system
* Coded loading furni on loading room !
* Fixed rooms_loaded count, fixed addflooritem handler, fixed inventory
* Added :sit command
* Fixed critical security issue, recoded handling system (more fast !)
* Enhanced handler (now use name system)
* Created auto-optimize system, enhanced class, finished catalog_pages
* Fixed minor bug
* Started catalogue
* Updated packet header name
* Started inventory, started edit of all packet id name (unfinished)


### Revision 24100

* Coded Z axis of pathfinder !
* Fixed updater
* Added message on startup if missing extension (pthreads)
* Fixed pathfinder bug if user click in same case
* Added change look handler
* Added load function on startup for : chat filter, quests, groups
* Added start time
* Cleaned file and updated handler doc.txt
* Added version file, coded about command
* Fixed console write on loading room
* Recoded UsersOnline,RoomsLoaded count system and enhanced config system

### Revision 23350

* Added :hal command
* Fixed chat bullet
* Fixed cmdlogs
* Fixed chatlogs
* Critical error fixed (class name)
* If missing line in bloon.conf, now show error
* Deleted useless file
* Fixed console writing disable in bloon.conf
* Added todo list
* Finished new config system (bloon.conf)
* Fixed pathfinder bug, started new config system

### Revision 22000

* Pathfinder multithreading fixed (thanks to krakjoe !)
* Added all update command
* Coded permission system
* Minor code fix.
* Minor system enhancement.
* Minor bugs fix.

### Revision 20500 (working Alpha #2)

* Rooms enhanced more (yea!)
* Pathfinder enhanced and fixed.
* Friend Tools bar started to be fixed (but amigo are not fixed)
* See-to-see system (play can see others players) working as well.
* Hotel Alert command (:ha) added.
* Minor code fix.
* Minor system enhancement.
* Minor bugs fix.

### Revision 20000 (working Alpha)

* Rooms enhanced
* Pathfinder coded
* Chat coded (shout,say)
* Chat color coded
* Minor bug fix
* Added some feature
* Added full support of PHP 5.4 (thanks to Ethis)

### Revision 13506

* Public navigator added.
* Rooms added, but not finish yet.
* Minor update.
* Minor fix.
* Fix for PHP5.4

### Revision 13105

* Starting add multithread (/w pthread) on PHP emulator
* Add pthreads php extension
* Fix a lot of minnor issue
* Fix the update-downloader
* Performance enhancement
* Fix a security major issue
* Fix handler system (Enhanced handler)
* Add a system in updater class, if you change revision code to "dev-custom", the script will not check the build. (Only for devs.)
* Add navigation features
* Fix a medium issue(when the user haven't any friends requests, he will be disconnected.. that's fixed)

### Revision 12000

* Added crossdomain function to core.
* Edit class function to static method.
* Bug fix …
* GetHeader correction.
* Correction of POO standards.
* Navigator bug fix …
* Some corrections about PDO use (BufferParser became static).
* Bug fix.
* Core file (and files calling it) has been edited to respect POO rules.
* Correct PHP console title.
