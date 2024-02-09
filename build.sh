#!/usr/bin/env bash

echo -e "\e[92m######################################################################"
echo -e "\e[92m#                                                                    #"
echo -e "\e[92m#                      Start PayU Builder                            #"
echo -e "\e[92m#                                                                    #"
echo -e "\e[92m######################################################################"

echo -e "Release"
echo -e "\e[39m "
echo -e "\e[39m======================================================================"
echo -e "\e[39m "
echo -e "Step 1 of 7 \e[33mRemove old release\e[39m"
# Remove old release
rm -rf CrehlerPayU CrehlerPayU-*.zip
echo -e "\e[32mOK"

echo -e "\e[39m "
echo -e "\e[39m======================================================================"
echo -e "\e[39m "
echo -e "Step 2 of 7 \e[33mBuild\e[39m"
cd ../../../
./bin/build-storefront.sh
./bin/build-administration.sh
cd custom/plugins/PayU/
echo -e "\e[32mOK"

echo -e "\e[39m "
echo -e "\e[39m======================================================================"
echo -e "\e[39m "
echo -e "Step 3 of 7 \e[33mCopy files\e[39m"
rsync -av --progress . CrehlerPayU --exclude CrehlerPayU
echo -e "\e[32mOK"


echo -e "\e[39m "
echo -e "\e[39m======================================================================"
echo -e "\e[39m "
echo -e "Step 4 of 7 \e[33mGo to directory\e[39m"
cd CrehlerPayU
echo -e "\e[32mOK"

echo -e "\e[39m "
echo -e "\e[39m======================================================================"
echo -e "\e[39m "
echo -e "Step 5 of 7 \e[33mDeleting unnecessary files\e[39m"
cd ..
( find ./CrehlerPayU -type d -name ".git" && find ./CrehlerPayU -name ".gitignore" && find ./CrehlerPayU -name "yarn.lock" && find ./CrehlerPayU -name ".php_cs.dist" &&  find ./CrehlerPayU -name ".gitmodules" && find ./CrehlerPayU -name "build.sh" ) | xargs rm -r
cd CrehlerPayU/src/Resources
rm -rf administration
cd ../../../
echo -e "\e[32mOK"


echo -e "\e[39m "
echo -e "\e[39m======================================================================"
echo -e "\e[39m "
echo -e "Step 6 of 7 \e[33mCreate ZIP\e[39m"
zip -rq CrehlerPayU-master.zip CrehlerPayU
echo -e "\e[32mOK"

echo -e "\e[39m "
echo -e "\e[39m======================================================================"
echo -e "\e[39m "
echo -e "Step 7 of 7 \e[33mClear build directory\e[39m"
rm -rf CrehlerPayU
echo -e "\e[32mOK"


echo -e "\e[92m######################################################################"
echo -e "\e[92m#                                                                    #"
echo -e "\e[92m#                        Build Complete                              #"
echo -e "\e[92m#                                                                    #"
echo -e "\e[92m######################################################################"
echo -e "\e[39m "
echo "   _____          _     _           ";
echo "  / ____|        | |   | |          ";
echo " | |     _ __ ___| |__ | | ___ _ __ ";
echo " | |    | '__/ _ \ '_ \| |/ _ \ '__|";
echo " | |____| | |  __/ | | | |  __/ |   ";
echo "  \_____|_|  \___|_| |_|_|\___|_|   ";
echo "                                    ";
echo "                                    ";
