
function tab_mystuff_loaded(response)
  {
    animatedcollapse.addDiv('intro', 'fade=9,speed=400,group=mystuff')
    animatedcollapse.addDiv('banners', 'fade=9,speed=400,group=mystuff,persist=1,hide=1')
    animatedcollapse.addDiv('emails', 'fade=9,speed=400,group=mystuff,hide=1')
    animatedcollapse.addDiv('aff_videos', 'fade=9,speed=400,group=mystuff,hide=1')

    animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
       //$: Access to jQuery
       //divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
      //state: "block" or "none", depending on state
    }

    animatedcollapse.init()

  }
