rm ../zipped/com_tks_agenda.zip
find `find . -type f | grep -v \~ | grep -v .git | grep -v doc  | grep -v build.sh`  -exec zip ../zipped/com_tks_agenda.zip {} +
