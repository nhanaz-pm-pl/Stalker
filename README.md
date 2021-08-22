![NhanAZ](images/NhanAZ.gif)

<h1>Track<img src="https://i0.wp.com/s1.uphinh.org/2021/08/06/icon.png" height="64" width="64"></img></h1><br/>

[![](https://poggit.pmmp.io/shield.state/Track)](https://poggit.pmmp.io/p/Track) [![](https://poggit.pmmp.io/shield.api/Track)](https://poggit.pmmp.io/p/Track) [![](https://poggit.pmmp.io/shield.dl.total/Track)](https://poggit.pmmp.io/p/Track) [![](https://poggit.pmmp.io/shield.dl/Track)](https://poggit.pmmp.io/p/Track)

This is a PocketMine plugin that helps staffs track players using commands.<br/>

<!-- <div align="center"> -->
<a href="https://poggit.pmmp.io/r/136167/Track.phar" target="_blank" title="Click to download the pluginm">
  <img src="https://user-images.githubusercontent.com/10297075/101246002-cb046780-3710-11eb-950f-ba06934b8138.png" </img>
</a>
<!-- </div> -->

# Features
- Allows selected staffs to watch players use commands to facilitate support.
- Selected people can review the commands they entered.
- Selected people are allowed to see console or rcon commands and players used.
- The console is allowed to see the commands the player uses.

# Setup
How to setup? Very simple! Follow the steps below:
- Step 1: Download the plugin and put it in plugins`(PocketMine-MP/plugins)`
- Step 2: Start the server to load `config.yml`
- Step 3: After server startup is complete, stop the server.
- Step 4: Go to the path `PocketMine-MP/plugin_data/Track/config.yml`
- Step 5: Then add the names of the employees who are allowed to view other players, consoles and rcon using the command at `config.yml`

# Future
- [X] Save the history of players using the command.
- [ ] Updated UI to make it easier to review someone's command usage history over a different period of time.
- [ ] And many other features recommended by plugin users, etc.

# Images
<div align="center"> <b>Tracking screen in-game</b> </div>

![ingame](images/ingame.jpg)

<div align="center"> <b>Tracking screen in console</b> </div>

![incls](images/incls.png)

# Configs
config.yml :
```
---
#Please enter the names of the staff who are authorized to follow the player using the command as the format is present below.
Trackers:
  - NhanAZ
  - Steve
  - Alex
...
```

<br/>

history.yml :
```
---
#This is where the system will save the command usage history.
'Wed 18/08/2021 03:57:24(AM) : Console': say Hi.
'Wed 18/08/2021 03:57:31(AM) : Console': say This is a feature to save the history
  of using the command.
...
```

# Contact
If you encounter an error or would like to contribute to my plugin, contact me via the platforms below:
- Discord: NhanAZ#9115
- Xbox: NhanAZ
- Zalo: @thanhnhanaz
- FaceBook: fb.com/thanhnhanaz

# License
[GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.html)
