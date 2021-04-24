file_con=$(iconv -f utf8 -t ascii//TRANSLIT /var/www/html/scripts/permanent_result | sed 's/[A-Z]//g')
file_url=$(urlencode -m $file_con)
part_one="https://127.0.0.1/api/interaction.php?text="
final="$part_one$file_url"
wget --no-check-certificate --background "$final" > /dev/null 2>&1

rm -f /var/www/html/scripts/permanent_result
rm -f wget*
rm -f interaction*

#back audio
audio=$(cat /var/www/html/scripts/audioState)
if [ "$audio" = "ACTIVE" ]; then
        case "$file_con" in
            *bonjou* ) play /var/www/html/scripts/audio/hello.wav;;
        esac

        case "$file_con" in
            *salu* ) play /var/www/html/scripts/audio/hello.wav;;
        esac

        case "$file_con" in
            *"comment ça va"* ) play /var/www/html/scripts/audio/imok.wav;;
            *"comment sa va"* ) play /var/www/html/scripts/audio/imok.wav;;
            *"comment ca va"* ) play /var/www/html/scripts/audio/imok.wav;;
            *"est ce que ca va"* ) play /var/www/html/scripts/audio/imok.wav;;
            *"est-ce que ca va"* ) play /var/www/html/scripts/audio/imok.wav;;
            *"est ce que sa va"* ) play /var/www/html/scripts/audio/imok.wav;;
            *"est-ce que sa va"* ) play /var/www/html/scripts/audio/imok.wav;;
            *"est ce que ça va"* ) play /var/www/html/scripts/audio/imok.wav;;
            *"est-ce que ça va"* ) play /var/www/html/scripts/audio/imok.wav;;
        esac
        
        case "$file_con" in
            *allume* ) play /var/www/html/scripts/audio/rightnow.wav;;
            *etein* ) play /var/www/html/scripts/audio/rightnow.wav;;
            *ouvre* ) play /var/www/html/scripts/audio/rightnow.wav;;
            *ferme* ) play /var/www/html/scripts/audio/rightnow.wav;;
        esac
fi
