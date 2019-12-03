#!/bin/sh

#!/bin/sh

echo "CREATE DATABASE IF NOT EXISTS \`twimg_test\` ;" | "${mysql[@]}"
echo "GRANT ALL ON \`twimg_test\`.* TO 'root'@'root_password' ;" | "${mysql[@]}"
echo 'FLUSH PRIVILEGES ;' | "${mysql[@]}"