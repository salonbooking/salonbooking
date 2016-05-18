find . -iname "*.php" > /tmp/salon-booking-system_file_list.txt
xgettext --keyword=_e --keyword=__ --from-code=utf-8 -d salon-booking-system  -f /tmp/salon-booking-system_file_list.txt --keyword=__ -o languages/salon-booking-system.pot
sed --in-place languages/* --expression=s/CHARSET/UTF-8/
