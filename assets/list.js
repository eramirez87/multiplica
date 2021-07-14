let colorList = {
    init: () =>{
        colorList.jsList = new List('listjs', {
            //valueNames: ['name'],
            page: 6,
            pagination: true,
            
          })
          .on('updated',function(a){
              setTimeout( () => {
                  colorList.setCss();
              },1 );
          }).on('searchStart',colorList.setCss());
    },
    setCss: () => {
        $('ul.pagination li').addClass('page-item');
        $('ul.pagination li a').addClass('page-link');
    } 
}

document.addEventListener('DOMContentLoaded', function() {
    colorList.init();
});
