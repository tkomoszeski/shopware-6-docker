//main entry file
//Add import module here

//use ./psh.phar administration:build
//if you are suing dockware you can use build-administration.sh file, to compile the sources
//add generates js files from public folder after executing build-administration.sh file
import './module/tk-bundle';

//import for adding product bundle directly inside product
import './extension/sw-product/view/sw-product-detail-base';
import './extension/sw-product/page/sw-product-detail';