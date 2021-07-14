let colorForm = {
    delete : (id) => {
        if( confirm('Esta accion no se puede deshacer') ){
            data = {'id': id};
            $.post(base_url + '/colors/drop/',data,function(re){
                if(re.success){
                    location.href= base_url + "colors";
                }
            },'json');
        }
    }
};