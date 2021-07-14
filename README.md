 # CUSTOM TAGS PLUGIN

<img src="https://discordapp.com/api/guilds/646732504744853518/widget.png?style=shield" alt="AquaDevs Discord"/>

 Don't forget to read the description before using this plugin! 

 📜Description: You can create unlimited tags. It has a menu where you can select to buy or equip the tags. 


 **⚙️Depends!**

```
 - EconomyAPI
```




 **📖How to create Tags!**

 Go to the config.yml file which is located in the plugin_data/CustomTags directory

```php
 # Display the tag of the player
chat-format: "{tag} {player}"

# Tags List
tags:
  levania:
    name: "§l§aLevania§r"
    perm: levania.tag
    cost: 1000
  darealaqua:
    name: "§l§bDaRealAqua§r"
    perm: darealaqua.tag
    cost: 5000
  aquadevs:
    name: "§l§bAqua§eDevs§r"
    perm: aquadevs.tag
    cost: 10000

# Main menu of the Tags Menu
# Category Selector Menu
menu-selector:
  title: "Tags Menu"
  content: "§7Your current money:§c ${money}{line}§7Your current tag:§c {tag}{line}§r§7Select a category"
  tags-button: "Available Tags{line}Click to Open"
  shop-button: "Buy Tags{line}Click to Open"
  exit-button: "§cExit the Menu"
# Equip the tag u want, if you have the permission
# Tags Menu
menu-tags:
  title: "Available Tags"
  content: "§7Your current tag: §c{tag}"
  locked-button: "{tag}{line}§r§cNot Available"
  unlocked-button: "{tag}{line}§r§aAvailable"
# Buy a tag
# Tags Shop Menu
menu-shop:
  title: "Tags Shop"
  content: "§7Your current money:§c ${money}"
  button: "{tag}{line}§r§c${cost}"
  # PureChat and PurePerm I currently use to set a player's permission but if you want you can use your own plugin.
  command: "setuperm {player} {permission}"
```

 **Subscribe to my Channel!**

 [YouTube Channel](https://youtube.com/c/AlexItz16%E3%83%84Nspe)



 **Join my Discord Servers!**

 [AquaDevs Discord](https://discord.gg/5pxFZHmsC7)

 [Levania Discord](https://discord.gg/Axa33MgXJ9)

 


 **📚What does the plugin have?**

 - [x] create unlimited tags
 - [x] tag shop
 - [x] custom menu
 - [x] chat format
 - [x] save the tag 
 - [x] economyAPI support



 **👥Credits**
```
 - DaRealAqua
```


 **📸Images**
 
 ![Image1](https://cdn.discordapp.com/attachments/769268554956013569/777895015329300480/20201116_145411)
