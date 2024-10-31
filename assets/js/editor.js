jQuery(function(){

   tinymce.PluginManager.add("mce_editor_js",function(editor){
      // adding dropdown to tinyMCE toolbar
      editor.addButton("editor_dropdown",{

         text: "Insert Field",
         tooltip: "This is placeholder dropdown",
         type:"menubutton",
         menu:[
            {
               text: "{{Name}}",
               value: "{{Name}}",
               onclick: function(){
                   editor.insertContent(this.value());
               }
            },
            {
               text: "{{Email}}",
               value: "{{Email}}",
               onclick: function(){
                  editor.insertContent(this.value());
               }
            }
         ]
	   });
   });
   function  alert_from_outside(){
   	alert("Hi I am running");	
   }
});
