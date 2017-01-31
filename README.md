
## Deploying
- git clone git@github.com:DanielFurmanov/doctors-shedule.git.
- copy and edit env.example to .env (mysql credentials)
- composer install (--no-scripts if necessary).
- php artisan migrate.
- npm install.
- npm run dev.

## Learning Laravel
App consists of one view, one controller and a few models;
###First screen:
![first](https://d1ro8r1rbfn3jf.cloudfront.net/ms_72063/SlucAKtolAkcBklAuYyhXmIqafTco0/title.doctors-schedule%2B-%2BGoogle%2BChrome%2B2017-01-31%2B09.53.53.png?Expires=1485932050&Signature=Yftw~yXRk3CrnFvRS2338-pnCu2a1HPtYlwYQ7DbomlxvuNUhD1mDev4~2x2D32O~S4zety49XlRrqUeSoyCyf2lSEbg207-eEBFMnh-hXHFz145M378sQudkONw~q6j-JXUY3EKyzsOsC84vBTJVlaZWLib3acHn-wV5cs1ZyWueetUg~r-ymxXedrrjfcRMc1y2LQvHhGq7y0HyW9WOWVMqfSTY~aADHup3hQtOWgBPkoUtOQC3NZoYSjTIurINtPDj6RFYvAhl9hzS0NribUnkLjO4YZ-5rPt2lvdG9T8cxjMB~2xAyhp7D13JpH8MEO3DP3KIVIyu9vk--kc6Q__&Key-Pair-Id=APKAJHEJJBIZWFB73RSA)<br>
Options: scroll all over the list, type name in top right input to get filtered results, press "записаться" to get to the second step
###Second Screen
![second](https://pp.vk.me/c626130/v626130741/4d5f7/Iqz0GvNzizQ.jpg)<br>
Options: go back to first screen, choose date from datepicker
![datepicker](https://pp.vk.me/c626130/v626130741/4d600/XzXu2vDDZWg.jpg)<br>
When date chosen list of available consultations appears
![consultations](https://pp.vk.me/c626130/v626130741/4d609/JvwtRq08cDo.jpg)<br>
When consultation time chosen modal with confirmation popups
![modal](https://pp.vk.me/c626130/v626130741/4d612/x3I9jjBBkrk.jpg)<br>
After confirmation, list of consultations refreshes and currently selected time is no more available
![done](https://pp.vk.me/c626130/v626130741/4d61b/sWzL9xyxHd8.jpg)
