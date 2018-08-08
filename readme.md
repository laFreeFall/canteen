# Canteen
#### It's a platform that helps managing canteen food. 
![Canteen report](https://i.imgur.com/g33doJs.png)

## What project uses
**Backend**
- [Laravel 5.6](https://github.com/laravel/laravel)
 
**Frontend**
- [Vue.js](https://github.com/vuejs/vue) *(for tiny page components requiring some kind of interaction)*
- [axios](https://github.com/axios/axios) *(for handling ajax requests)*
- [BootstrapVue](https://github.com/bootstrap-vue/bootstrap-vue) *(for fast and beauty design prototyping)*
- [vue-snotify](https://github.com/artemsky/vue-snotify) *(for toasts/notifications)*
- [epic spinners](https://github.com/epicmaxco/epic-spinners) *(for displaying loader/spinner before data load)*
- [v-tooltip](https://github.com/Akryum/v-tooltip) *(for tooltips on hover on a dish icon)*
- [vue-awesome](https://github.com/Justineo/vue-awesome) *(for font-awesome icons integration)*
- [vue-scrollto](https://github.com/rigor789/vue-scrollto) *(for smooth page scrolling to the needed place by #hash)*
- [vue-wait](https://github.com/f/vue-wait) *(for data loading management)*
- [Vue.Draggable](https://github.com/SortableJS/Vue.Draggable) *(for allowing reordering dishes just by its dragging)*

## Installation
Download the project
`git clone lafreefall/canteen projectname`

`cd projectname`

- Backend

1. Copy .env.example, rename to .env and fill with your environment data
`cp .env.example .env`
2. Generate app key
`php artisan key:generate`

3. Install Composer
`composer install`

4. Create database and put its name in your `.env` file

5. Create and populate database tables
`php artisan migrate --seed`

6. Host
`php artisan server` to start on [localhost:8000](http://localhost:8000/)

- Frontend

1. Go to project folder and install all the dependencies
`npm install`

2. Change axios default url to yours in `projectname/resources/assets/js/app.js` on the 30 line `axios.defaults.baseURL = 'YOUR_URL'`

3. If you want to change something and need a watcher
`npm run dev`

If you need to bundle final file
`npm run prod`

## Brief project description

 
This app helps with recording and tracking canteen activity. It's assumed that user has a group of people he wants to manage. Each person may eat different dishes for different prices in different days or be absent and pay weekly fee for it. You set eaten dishes, its prices and app automatically calculates all the information.

  ### Report page
 
 Initially when you visit home page you would be redirected to the report for the current week depending on the date.
 
 ![Full report page](https://i.imgur.com/g33doJs.png)
 
 Each eaten dish displays in corresponding cell at the intersection of pupil name and week day as a tiny button with dish abbreviature. On hover you can see full dish title and its price for this day.
 
 ![Eaten dishes](https://i.imgur.com/iqHYkPG.png)
 
  If you want to add a dish to a pupil you should click on a plus sign and choose one you need in the appeared popover. Here you can select a dish,  template (it'll be described below) or pick absent.
  If a pupil refused to eat a dish, you can remove it just by clicking on its button.
  If a pupil was absent, you don't need to manually click to remove all his dishes. You can just select botton option - absent. All pupil's dishes will be removed and absent icon will be set instead.

  ![Adding new dish](https://i.imgur.com/9Nt6Sf3.png)

  Templates are predefined sets of pupil-dish pairs. For example, your canteen has two different menus depending on ingredients availability. First three days they cook one set of dishes, two others - another. In this case you can create two templates for both dishes sets and after that just pick template on any pupil and it will be applied to all puplils on chosen day. It's very useful not to add every dish to every pupil manually if they often eat the same set of dishes.
  
  ![Templates](https://i.imgur.com/YQp02lK.png)
  
  On the bottom of each day outcomes are displayed (total amount of each dish eaten by pupils that day and its total price)
  
  ![Day outcomes](https://i.imgur.com/WFB89bm.png)

  On the right of the report page money outcomes are displayed (this time groupped by pupil, not by day).
  On the fist column sum of wasted money by pupil this week is displayed (if he has ate two buns $3 each, value of the cell would be $6).
  Second and third column are opposing and displays financial result: if pupil ate on more amount of money than he paid this week, second column will be active - highlighted red and amount of debt will be displayed, if pupil paid more money than he wasted eating dishes this week - third column will be highlighted green with the rest of the money.
  
  ![Week outcomes](https://i.imgur.com/CZHZ7mH.png)
  
  At the bottom of the page table with dishes prices displays. Here you can set price of each dish for each day. Report table with all outcomes will be recalculated instantly after your price changes.
  
  ![Dishes prices](https://i.imgur.com/6ouGHQ4.png)
  
  There is also an opportunity to download your report as a .docx Word file by clicking on the blue word button on the right of the week title.
  
  ![Word report](https://i.imgur.com/1pAXWYU.png)
  
  ### Calendar page
  
  The next page is Calendar, where you can see all school weeks. Here you can click on a day to toggle it status from day of school to day off. By default all weekends are set as day off, highlighted gray on the calendar and aren't displayed on the reports. If some holiday happened in the middle of the week and you dont want to empty day existing on the report table, you can click on it to make it day off, so it'll be hidden.
  
  ![Calendar](https://i.imgur.com/4bqnsxk.png)
  
  ### Pupils page
  
  Here you can manage your pupils in different way.
  
  ![Pupils page](https://i.imgur.com/7804Qr6.png)
  
  First of all, you can add new pupils by clicking on the corresponding button at the top.
  
  ![Adding new pupil](https://i.imgur.com/thcNN5R.png)
 
  The same modal filled with pupil's data will open if you click on the button highlighted on the first image of this section, intended for editing pupil. If a pupil has left school, you can click on the button next to the previous one to disable him. He'll be moved to the section of inactive pupils and won't be displayed on the next weeks.
  
  ### Dishes page
  
  Similar page exists for dishes managing. Because it makes little sense to sort dishes in alphabetical order as we do with pupils, here you can decide for yourself which order you like more and just drag them to reorder. New order of the dishes is stored and applied instantly.
  
  ![Dishes page](https://i.imgur.com/bfEWIoE.png)
  
  Modal for adding and editing dishes is a little bit larger because of specifities of russian language.
  
  ![Edit dish](https://i.imgur.com/4WjN9lm.png)
  

> If you want to see more - watch the [full screenshots album on imgur](https://imgur.com/a/IxcUO3g).
