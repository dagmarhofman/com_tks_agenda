#archiveer het project

zip -r ../zipped/com_tks_agenda.zip .

#upload de update server xml

#de tks_template_update.xml gaat naar http://takties.alt.guestbox.nl/tks_template_update.xml

#sshpass -f /etc/digi_guestbox_pass.txt scp -P 2020 tks_template_update.xml digi@www.guestbox.nl:/var/www/takties/localdata/tks_template_update.xml

#de zip gaat naar http://takties.alt.guestbox.nl/mod_tks_template.zip

#sshpass -f /etc/digi_guestbox_pass.txt scp -P 2020 ../../zipped/mod_tks_template.zip digi@www.guestbox.nl:/var/www/takties/localdata/mod_tks_template.zip

#de maintainance html gaat naar http://takties.alt.guestbox.nl/tks_template_update.html

#sshpass -f /etc/digi_guestbox_pass.txt scp -P 2020 tks_template_maintainance.html digi@www.guestbox.nl:/var/www/takties/localdata/tks_template_maintainance.html
