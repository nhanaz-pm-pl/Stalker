# General

[![](https://poggit.pmmp.io/shield.state/Track)](https://poggit.pmmp.io/p/Track) [![](https://poggit.pmmp.io/shield.api/Track)](https://poggit.pmmp.io/p/Track) [![](https://poggit.pmmp.io/shield.dl.total/Track)](https://poggit.pmmp.io/p/Track) [![](https://poggit.pmmp.io/shield.dl/Track)](https://poggit.pmmp.io/p/Track)

This is a plugin written in PHP programming language and running on PocketMine platform that works stably at API [4.0.0] It allows staff to track the commands players use.<br/>

# Features
- Allows selected staffs to watch players use commands to facilitate support.
- Selected people can review the commands they entered.
- Selected people are allowed to see console or rcon commands and players used.
- The console is allowed to see the commands the player uses.

# Setups
How to setup? Very simple! Follow the steps below:
- Step 1: Put plugin in plugins`(PocketMine-MP/plugins)`
- Step 2: Start the server to load `Configs`
- Step 3: After the server startup is complete, stop the server.
- Step 4: Go to the path `PocketMine-MP/plugin_data/Track/config.yml`
- Step 5: General configuration settings in `config.yml`

# Futures
- [X] Save the history of players using the command.
- [X] Convert tracking notifications to Unicode fonts
- [X] Log the use of commands used by players.
- [ ] UI Form for editing config.
- [ ] Connect with Discord.
- [ ] Connect with Messenger?
- [ ] And many other features recommended by plugin users, etc.

# Images
<div align="center"> <b>Tracking screen in-game</b> </div>

<img src="https://github.com/NhanAZ/Images/blob/master/handlefont.jpg" />

<div align="center"> <b>Tracking screen in console</b> </div>

<img src="https://github.com/NhanAZ/Images/blob/master/incls.png" />

# Configs
## config.yml
```
---
#Set UnicodeFont: true to UnicodeFont: false if you don't want to track messages converted to unicode fonts
UnicodeFont: true #UnicodeFont: true (Recomend using)

DeleteHistory:
#Set onEnable: true if you want to clear the player's command usage history when the server enable.
#Set onEnable: false if you don't want to clear the player's command usage history when the server enable.
  onEnable: false #onEnable: true (Recomend using)

#Set onDisable: true if you want to clear the player's command usage history when the server disable
#Set onDisable: false if you don't want to clear the player's command usage history when the server disable
  onDisable: false #onDisable: false (Recomend using)

#This is the message when the command usage history has been cleared.
NoticeRemoved: "Removed player command usage history (Disabled at config.yml)"

#Please enter the names of the staff who are authorized to follow the player using the command as the format is present below.
Trackers:
  - NhanAZ
  - Steve
  - Alex
...

```
## history.yml
```
---
#This is where the system will save the command usage history.
'Wed 18/08/2021 03:57:24(AM) : CONSOLE': say Hi.
'Wed 18/08/2021 03:57:31(AM) : CONSOLE': say This is a feature to save the history
  of using the command.
...
```

# Contacts
If you encounter an error or would like to contribute to my plugin, contact me via the platforms below:
- Discord: NhanAZ#9115
- Xbox: NhanAZ
- Zalo: @thanhnhanaz
- FaceBook: fb.com/thanhnhanaz

# License
[GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.html)
