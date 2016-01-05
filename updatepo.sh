find . -iname "*.php" > /tmp/salon-booking-system_file_list.txt
xgettext --from-code=utf-8 -d salon-booking-system -j -f /tmp/salon-booking-system_file_list.txt --keyword=__ --keyword=_e -o languages/salon-booking-system-en_EN.po
xgettext --from-code=utf-8 -d salon-booking-system -j -f /tmp/salon-booking-system_file_list.txt --keyword=__ --keyword=_e -o languages/salon-booking-system-en_US.po
xgettext --from-code=utf-8 -d salon-booking-system -j -f /tmp/salon-booking-system_file_list.txt --keyword=__ --keyword=_e -o languages/salon-booking-system-it_IT.po
xgettext --from-code=utf-8 -d salon-booking-system -j -f /tmp/salon-booking-system_file_list.txt --keyword=__ --keyword=_e -o languages/salon-booking-system-de_DE.po
xgettext --from-code=utf-8 -d salon-booking-system -j -f /tmp/salon-booking-system_file_list.txt --keyword=__ --keyword=_e -o languages/salon-booking-system-fr_FR.po
xgettext --from-code=utf-8 -d salon-booking-system -j -f /tmp/salon-booking-system_file_list.txt --keyword=__ --keyword=_e -o languages/salon-booking-system-es_ES.po
