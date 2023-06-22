const profileBtn = document.getElementById('profile')
const profileMenu = document.getElementById('profileMenu')
const notifyMenu = document.getElementById('notifyMenu')
const notificationBtn = document.getElementById('notifications')
// const menuBtn = document.getElementById('pages')

profileBtn.addEventListener('click', ()=>{
    profileMenu.classList.toggle('hide')
})

notificationBtn.addEventListener('click', ()=>{
    // console.log("!!")
   notifyMenu.classList.toggle('hide')

})
