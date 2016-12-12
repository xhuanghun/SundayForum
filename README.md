# SundayForum
Forum Web Application base on Symfony framework

### Conception Description

This is a forum web application, but it could be different picture from phpbb or other community application. It could be fallen deeply in fantasy. Everything works well with smart ideas, sometimes even be better than imagination by creator. As it works  and finishes some jobs by itself, and the results could be out of your thinking. There are some features and functions as below todo list.

### Finished Job List

- FrontPage
![FrontPage]
(https://raw.githubusercontent.com/TonyGao/SundayForum/master/src/Sunday/ForumBundle/Resources/doc/image/Sunday%20Forum%2001.png)

- Menu for Doctrine Entity 
![Menu]
(https://raw.githubusercontent.com/TonyGao/SundayForum/master/src/Sunday/ForumBundle/Resources/doc/image/Sunday%20Forum%20Menu%2002.gif)

- Igor Design and page layout
![IgorDesign]
(https://raw.githubusercontent.com/TonyGao/SundayForum/master/src/Sunday/ForumBundle/Resources/doc/image/Sunday%20Forum%20Igor%2003.gif)

- Igor Command
![IgorCommand]
(https://raw.githubusercontent.com/TonyGao/SundayForum/master/src/Sunday/ForumBundle/Resources/doc/image/Sunday%20Forum%20igortest%2009.gif)

- Single Post Page
![SinglePost]
(https://raw.githubusercontent.com/TonyGao/SundayForum/master/src/Sunday/ForumBundle/Resources/doc/image/Sunday%20Forum%20post%2004.png)

- User Center Page(static html)
![UserCenter]
(https://raw.githubusercontent.com/TonyGao/SundayForum/master/src/Sunday/ForumBundle/Resources/doc/image/Sunday%20Forum%20pantheon%2005.png)

- User Register Page
![UserRegister]
(https://raw.githubusercontent.com/TonyGao/SundayForum/master/src/Sunday/ForumBundle/Resources/doc/image/Sunday%20Forum%20register%2006.gif)

### Todo List

- Web design. We use Semantic-UI as its UI library.
  - FrontPage design (done)
  - UserCenter design (done)
  - loginPage and register design (done)
  - backend Admin (we use EasyAdminBundle)
- Basic Function
  - post (singlePost done)
  - comment, postscript
  - vote up, vote down, advanced comments order 
- Ecological system design
  - User Health, Organization, Levels, roles, etc
  ![ConceptionImage]
  (https://github.com/TonyGao/SundayForum/blob/master/src/Sunday/ForumBundle/Resources/doc/image/Sunday%20Forum%20Organization%2007.png?raw=true)
  - Witchcraft of user
    - For other people: attack, defense, cure, dead, reborn, etc
    - For Post: only get the posts belongs your organization, purge a post, lock a post, hide the content of a post, stick a post, etc
- Servant
  - His name is Igor at very begin version , ability of customization  for it  will be added in the future version. But now we are focus on the only one character called Igor.
  - He is robot, he speak, he talk with you, and answer you (by command suite and searching)
  - He is the interface of the website, he give you all the information except common view of posts.
  - Daemon Structure Image
  ![DaemonStructure]
  (https://raw.githubusercontent.com/TonyGao/SundayForum/master/src/Sunday/ForumBundle/Resources/doc/image/Sunday%20Forum%20Daemon%2008.png)
- Data Model Design
  - Design Doctrine ORM Entity
  - Achieve Entity Repository
- Crawler
  - Implement a web crawler for some specific jobs and needs of resources.
  - For answering questions of user, etc

