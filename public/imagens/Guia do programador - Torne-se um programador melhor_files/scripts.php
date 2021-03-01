
$(document).ready(function(){

    $('form input').keypress(function(e){ 
        if(e.which == 13){ 
            e.stopPropagation();
            e.preventDefault(); 
        } 
    });

    if($(".select2").length > 0){ 
        $(".select2").select2({
            placeholder: language_array['select'],
            width: '100%',
        });
    }

    if($(".select-students").length > 0){ 
        $(".select-students").select2({
            placeholder: language_array['select'],
            width: '100%',
            ajax: {
                url: $('[linkrefresh]').attr('linkrefresh'),
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function (params) {
                    return {
                        search: params.term,
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.students,
                    };
                },
                cache: true
            },
            minimumInputLength: 3,
            templateResult: function(item, li_element){
                return $('<span>' + item.nome + ' <span>');
            }
        });
    }
    
	$('#raty').raty();
	$('#score').raty(
		{ 
			score: function () { 
				return $(this).attr('data-score'); 
			} 
		}
	);

	$('#btn_dp').click(function(e){
        $(this).hide();
        ajax($('#cad_dp').attr('action'), $('#cad_dp').serialize(), 'POST', 'json', null, null, null, function(jsonReturn){
		  validAjax($('#btn_dp'), jsonReturn);
        });
	});

    var order = [];
    var arrcol = [];

	var order = [[4, "asc"], [3, "desc"]];
    var arrcol = [];
    if(client_tipo != 1){
        order = [[3, "desc"]];
        arrcol = [
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 }
        ];
    }
    else{
        arrcol = [
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 }
        ];
    }

	/* paginacao /depoimentos/ */
	$('#table-depoimentos').dataTable({
        "order": order,
        "stateSave": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "../../../plataforma/brief/index.php?action=briefPaginate&origin=" + getOrigin(),
        columns: arrcol,
        "oLanguage": datable_language,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        	nRow = $(nRow);
            if(!aData[6]){
                aData[9] = null;
            }
            nRow.addClass('pointer')
            	.click(function(){
            		window.location.href = $(this).parent().attr('trlink') + aData[7];
            	})
            	.find('td')
            	.addClass('val_md')
            	.addClass('text-center')
            	.eq(0)
            	.text('')
            	.append('<img src="'+prepareImagesToView(aData[9], 'profiles', 45, 45, 100)+'" width="45" height="45" class="img-circle" data-tooltip="tooltip" onerror="this.src=\''+ imgonerror + '\'" title="'+aData[8]+'" alt="'+aData[8]+'">');
            nRow.find('td:eq(1)').removeClass('text-center');
            if(aData[2] > 0){
            	var title = '';
            	if(aData[2] == 1){
            		title = aData[2] + ' ' + language_array["star"];
            	}else{
            		title = aData[2] + ' ' + language_array["stars"];
            	}
            	var stars = '';
            	for(var i = 0; i < aData[2]; i++){
                    stars += '<i class="fa fa-star"></i>';
            	}
            	nRow.find('td:eq(2)').html('<span data-tooltip="tooltip" title="" data-original-title="'+title+'">'+stars+'</span>');
            }

            if(aData[4] > 0){
            	nRow.find('td:eq(4)').html('<span data-tooltip="tooltip" title="" data-original-title="'+language_array['approved']+'"><i class="fa fa-check-circle"></i></span>'); 
            }else{
 				nRow.find('td:eq(4)').html('<span data-tooltip="tooltip" title="" data-original-title="'+language_array['waiting_approval']+'"><i class="fa fa-clock-o"></i></span>');
 			}
        }
    });

    if(client_language != 1){
        $(".dataTables_filter input").attr("placeholder", "Search...");
    } 
    
    $('#cbo_cursos_export_brief').change(function(){
		$('#btn_export_brief').text(language_array['export']+'...');
	});

	$('#cbo_alunos_export_brief').change(function(){
		$('#btn_export_brief').text(language_array['export']+'...');
	});

	$('#btn_export_brief').click(function(){
		$('#btn_export_brief').text(language_array['wait']+'...');
		var url = $('#btn_export_brief').attr('origem');
		url = url.replace("all", $('#cbo_cursos_export_brief').val());
		url = url.replace("alls", $('#cbo_alunos_export_brief').val());

		$('#btn_export_brief').attr('href', url);
	});

	$('#cbo_cursos_export_brief').val($('#cbo_cursos_export_brief').val()).trigger('change');
	$('#cbo_alunos_export_brief').val($('#cbo_alunos_export_brief').val()).trigger('change');

});
$(document).ready(function(){

    $('form input').keypress(function(e){ 
        if(e.which == 13){ 
            e.stopPropagation();
            e.preventDefault(); 
        } 
    });
    
    $('.btn_add_module').unbind('click').click(function(e){
        var btn = $(this);
        var course_id = $('[name="course_id"]').val();
        alertModal({
            title: language_array['new'] + ' ' + language_array['module'],
            text: '<form id="formNewModuleCourse" class="form-horizontal">'+
                        '<div class="form-group">'+
                            '<label for="status" class="col-lg-2 control-label">'+ language_array['status'] +'</label>'+
                            '<div class="col-lg-4">'+
                                '<select class="form-control " id="status" name="status">'+
                                    '<option value="1" selected="selected" >'+ language_array['published'] +'</option>'+
                                    '<option value="0">'+ language_array['draft'] + '</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="modulo" class="col-lg-2 control-label">'+ language_array['name']  +'</label>'+
                            '<div class="col-lg-10">'+
                                '<input type="text" class="form-control" name="modulo" id="modulo" placeholder="'+ language_array['enter_name_moudle'] +'" required="required">'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="professor_id" class="col-lg-2 control-label">'+ language_array['teacher']  +'</label>'+
                            '<div class="col-lg-10 div_teacher">'+
                                
                            '</div>'+
                        '</div>'+
                        '<input type="hidden" name="course_id" value="'+ course_id +'" readonly="readonly" />'+
                        '<div class="form-group">'+
                            '<label for="descricao" class="col-lg-2 control-label">' + language_array['description'] +'</label>'+
                            '<div class="col-lg-10">'+
                                '<textarea class="form-control autosize" name="descricao" id="descricao" placeholder="'+ language_array['description_module'] +'"></textarea>'+
                                '<p class="text-muted">* '+ language_array['optional'] +'</p>'+
                            '</div>'+
                        '</div>'+
                   '</form>',
            fnLoad: function(modal_id, modal){
                var selectClone = $('[name="professor_id"]').clone();
                selectClone.removeClass('select2, select2-hidden-accessible');
                modal.find('.div_teacher').append(selectClone);
                selectClone.select2({
                    width: '100%',
                });
            },
            utilizaConfirm: true,
            titleConfirm: language_array['register'],
            fnConfirm: function(modal_id, modal){
                var act = btn.attr('action');
                var btn_modal = modal.find('button.btn-custom');
                btn_modal.hide();
                ajax(act, modal.find('form').serialize(), 'POST', 'json', null, null, null, function(response){
                    validAjax(btn_modal, response, true, modal.find('.modal-body'));
                    if(response.error == false){
                        setTimeout(function(){
                            modal.close(true);
                        }, 2500);

                        var select = $('select[name="modulo"]');
                        select.append('<option selected="selected" teacher="'+response[':professor_id']+'" status="'+response[':status']+'" value="'+response['id']+'">'+response[':modulo']+'</option>');
                        select.select2("destroy");
                        select.select2();
                        select.trigger('change');
                    }
                });
            },
            utilizaCancel: false,
            close: false
        });
    });

    $('select[name="modulo"]').change(function(e){
        $('.div_warning').addClass('hidden');
        var op = $('[name="modulo"] [value="'+this.value+'"]');
        var status = op.attr('status');
        if(status == 0){
            $('.div_warning').removeClass('hidden');
        }

        if(this.value > 0){
            $('#professor_id').val(op.attr('teacher')).trigger('change');
        }
    });

    $('#permanencia').datetimepicker({pickDate: false});
    $('.input-group.tempo').datetimepicker({pickDate: false});
    $('.cbo_lesson_status').change(function(e){
        if($('.cbo_lesson_status').val() == 1){
            $('.div_notify_students').removeClass('hidden');
        }else{
            $('.div_notify_students').addClass('hidden');
        }
    });
    
    if($('#ul_drag').length > 0){

        var xhr = null;

        ajax($('#ul_drag').attr('action'), null, 'GET', 'json', null, null, null, function(data){
            for (var i = 0; i < data.modules.length; i++) {
                var li = $('<li>');
                var span = $('<span>');
                var ul = $('<ul class="ul-lessons" moduleid="'+data.modules[i].modulo_id+'">');
                for (var j = 0; j < data.modules[i].lessons.length; j++) {
                    var liA = $('<li courseid="'+data.modules[i].curso_id+'" moduleid="'+data.modules[i].modulo_id+'" lessonid="'+data.modules[i].lessons[j].aula_id+'">');
                    liA.html('<span class="orderL">' +(parseInt(j, 10) + 1)+ '</span> - '+ data.modules[i].lessons[j].aula);
                    ul.append(liA);
                }
                span.html('<span class="order">' + (parseInt(i, 10) + 1) + '</span> - ' + data.modules[i].modulo);
                li.append(span);
                li.append(ul);
                $('#ul_drag').append(li);
            }

            $('#ul_drag').sortable(
                {
                    activate: function( event, ui ) {
                        //console.log(ui);
                    },

                    change: function( event, ui ) {
                        //console.log(ui);
                    },

                    beforeStop: function( event, ui ) {
                        var arr = [];
                        $('.ul-lessons').each(function(i, item){
                            var item = $(item);
                            var arrL = [];
                            $(item.find('li[lessonid]')).each(function(j, lesson){
                                var lesson = $(lesson);
                                arrL.push({
                                    lessonid: lesson.attr('lessonid'),
                                    oldmoduleid: lesson.attr('moduleid'),
                                    courseid: lesson.attr('courseid')
                                });
                            });
                            var obj = {
                                moduleid: item.attr('moduleid'),
                                lessons: arrL,
                            }
                            arr.push(obj);
                        });
                        
                        var urlOrder = $('#ul_drag').attr('saveorderM');
                        var dataOrder = objSerialize({lesson: JSON.stringify(arr)});
                        
                        if(xhr){
                            xhr.abort();
                        }

                        xhr = ajax(urlOrder, dataOrder, 'POST', 'json', null, null, null, function(jsonReturn){
                            xhr = null;
                        });

                        $('#ul_drag').find('span.order').each(function(i, item){
                            $(item).text((parseInt(i, 10) + 1));
                        });
                    }
                }
            );

            $('.ul-lessons').sortable(
                {
                    connectWith: ".ul-lessons",
                    activate: function( event, ui ) {
                        //console.log(ui);
                    },
                    change: function( event, ui ) {
                        //console.log(ui);
                    },
                    beforeStop: function( event, ui ) {
                        var arr = [];
                        $('.ul-lessons').each(function(i, item){
                            var item = $(item);
                            var arrL = [];
                            $(item.find('li[lessonid]')).each(function(j, lesson){
                                var lesson = $(lesson);
                                lesson.find('span.orderL').text((parseInt(j, 10) + 1));
                                arrL.push({
                                    lessonid: lesson.attr('lessonid'),
                                    oldmoduleid: lesson.attr('moduleid'),
                                    courseid: lesson.attr('courseid')
                                });
                                lesson.attr('moduleid', item.attr('moduleid'));
                            });
                            var obj = {
                                moduleid: item.attr('moduleid'),
                                lessons: arrL,
                                
                            }
                            arr.push(obj);
                        });
                        
                        if(xhr){
                            xhr.abort();
                        }

                        var urlOrder = $('#ul_drag').attr('saveorderL');
                        var dataOrder = objSerialize({lesson: JSON.stringify(arr)});
                        xhr = ajax(urlOrder, dataOrder, 'POST', 'json', null, null, null, function(jsonReturn){
                            xhr = null;
                        });
                    }
                }
            );
            
        });
    }

    if($(".select2").length > 0){ 
        $(".select2").select2({
            placeholder: language_array['select'],
            width: '100%',
        });
    }
    
    $('#btn_form_config_aula').click(function(e){
        $('#btn_form_config_aula').hide();
        ajax($('#form_config_aula').attr('action'), $('#form_config_aula').serialize(), 'POST', 'json', null, null, null, function(jsonReturn){
            validAjax($('#btn_form_config_aula'), jsonReturn);
        });
    });

    $('#btn_form_import_aula').click(function(e){
        $('#btn_form_import_aula').hide();
        ajax($('#form_import_aula').attr('action'), $('#form_import_aula').serialize(), 'POST', 'json', null, null, null, function(jsonReturn){
            validAjax($('#btn_form_import_aula'), jsonReturn);
        });
    });

    $('#btn_clean_config_aula').click(function(){
        $("#config_aula_aplicar").val(2).trigger('change');
        $("#config_aula_requisito").val(0).trigger('change');
        $("#config_aula_tipo_data").val(0).trigger('change');
        $("#config_aula_data_inicio").val('').trigger('change');
        $("#config_aula_periodo_inicio").val(0).trigger('change');
        $("#config_aula_periodo_fim").val(0).trigger('change');
        $("#config_aula_valida_tempo").val(0).trigger('change');
        $("#config_aula_permanencia").val('').trigger('change');
        $("#config_aula_limita_visualizacoes").val(0).trigger('change');
        $("#config_aula_num_visualizacoes").val(0).trigger('change');
        ajax($('#form_config_aula').attr('action'), $('#form_config_aula').serialize(), 'POST', 'json');
    });

    $('#btn_form_controll_lesson').click(function(e){
        ajax($('#form_controll_lesson').attr('action'), $('#form_controll_lesson').serialize(), 'POST', 'json', null, null, null, function(jsonReturn){
            validAjax($('#btn_form_controll_lesson'), jsonReturn, true);
        });
    });

    $('.cbo_requisito').change(function(e){ 
        if($('.cbo_requisito').val() == 1){    
            $('.div_tempominimo').removeClass('hidden');   
        }else{ 
            $('.div_tempominimo').addClass('hidden');  
        }  
    });    
   
    $('.cbo_limita_visualizacoes').change(function(e){ 
        if($('.cbo_limita_visualizacoes').val() == 1){ 
            $('.div_visualizacao').removeClass('hidden');  
        }else{ 
            $('.div_visualizacao').addClass('hidden'); 
        }  
    });    
   
    $('.cbo_libera_aula').change(function(e){  
        if($('.cbo_libera_aula').val() == 0){  
            $('.div_tempo_variavel').addClass('hidden');   
            $('.div_tempo_fixo').addClass('hidden');   
        }else if($('.cbo_libera_aula').val() == 1){    
            $('.div_tempo_fixo').removeClass('hidden');    
            $('.div_tempo_variavel').addClass('hidden');   
        }else{ 
            $('.div_tempo_fixo').addClass('hidden');   
            $('.div_tempo_variavel').removeClass('hidden');    
        }  
    });

    $('.cbo_tipo_data').change(function(e){ 
        if($('.cbo_tipo_data').val() == 1){    
            $('#label_periodo').text(language_array['period_after_enrollment']);   
        }else{ 
            $('#label_periodo').text(language_array['period_after_lesson_conclusion']);    
        }  
    });

    $('.cbo_config_aula_tipo_data').change(function(e){ 
        if($('.cbo_config_aula_tipo_data').val() == 1){    
            $('#label_periodo').text(language_array['period_after_enrollment']);   
        }else{ 
            $('#label_periodo').text(language_array['period_after_lesson_conclusion']);    
        }  
    });

    $('#btn_form_player_subtitle').click(function(e){
        var data = new FormData($('#form_player_subtitle')[0]);
        var div_bar = '#loading-subtitle';
        $('#btn_form_player_subtitle').hide();
        var jsonReturn = ajax($('#form_player_subtitle').attr('action'), data, 'POST', null, false, false, div_bar, function(jsonReturn){
            validAjax($('#btn_form_player_subtitle'), jsonReturn);
        });
    });

    $('#btn_form_cad_aula').click(function(e){
        var file = $('[name="pdf"]');
        var file2 = $('[name="storage"]');
        var file3 = $('[name="storage2"]');
        if(file.length > 0 && file[0].files[0].size > 26214400){
            alertElement (file, language_array['limit_max'] + ' 25MB');
            return;
        }else if(file2.length > 0 && file2[0].files[0].size > 3221225472){
            alertElement (file2, language_array['limit_max'] + ' 3GB');
            return;
        }else if(file3.length > 0 && file3[0].files[0].size > 3221225472){
            alertElement (file3, language_array['limit_max'] + ' 3GB');
            return;
        }else{
            if($(this).parents('.modal').length > 0){
                $(this).hide();
            }

            var data = new FormData($('#form_cad_aula')[0]);
            $(this).attr('old-text', $(this).text()).text(language_array['wait'] + '...').css('pointer-events', 'none');
            
            var div_bar = '';
            if($('[name="capa"]').length > 0 || $('#tipo_aula').val() == 'storage' || $('#tipo_aula').val() == 'storage2' || $('#tipo_aula').val() == 'mp3' || $('#tipo_aula').val() == 'pdf'){
                div_bar = '#loading-aula';
            }

            var jsonReturn = ajax($('#form_cad_aula').attr('action'), data, 'POST', null, false, false, div_bar, function(jsonReturn){
                if($('#tipo_aula').val() != 'livestream' || jsonReturn.error){
                    $('#btn_form_cad_aula').hide();
                    validAjax($('#btn_form_cad_aula'), jsonReturn);
                }else{
                    setTimeout(function(){
                        window.location.replace(jsonReturn.link);
                    }, 500);
                }
                    
            });
        }
    });

    $('#btn_form_cad_aula_edit').click(function(e){
        var file = $('[name="pdf"]');
        var file2 = $('[name="storage"]');
        var file3 = $('[name="storage2"]');
        if(file.length > 0 && file[0].files[0].size > 26214400){
            alertElement (file, language_array['limit_max'] + ' 25MB');
            return;
        }else if(file2.length > 0 && file2[0].files[0].size > 3221225472){
            alertElement (file2, language_array['limit_max'] + ' 3GB');
            return;
        }else if(file3.length > 0 && file3[0].files[0].size > 3221225472){
            alertElement (file3, language_array['limit_max'] + ' 3GB');
            return;
        }else{
            if($(this).parents('.modal').length > 0){
                $(this).hide();
            }

            var data = new FormData($('#form_cad_aula')[0]);
            $(this).attr('old-text', $(this).text()).text(language_array['wait'] + '...').css('pointer-events', 'none');
            
            var div_bar = '';
            if($('[name="capa"]').length > 0 || $('#tipo_aula').val() == 'storage' || $('#tipo_aula').val() == 'storage2' || $('#tipo_aula').val() == 'mp3' || $('#tipo_aula').val() == 'pdf'){
                div_bar = '#loading-aula';
            }

            var jsonReturn = ajax($('#form_cad_aula').attr('action'), data, 'POST', null, false, false, div_bar, function(jsonReturn){
                validAjax($('#btn_form_cad_aula_edit'), jsonReturn, false);  
            });
        }
    });

    $('[type="file"]').change(function(e){
        var extension = this.files[0].name.split('.').pop().toLowerCase();
        if(whitelist.indexOf(extension) == -1){
            alertElement ($(this), language_array['extension_not_allowed']);
            $('#btn_form_cad_arquivo').attr('disabled', 'disabled');            
        }else{
            $('#btn_form_cad_arquivo').removeAttr('disabled');
        }
    });

    $('select[name="options"]').change(function(e){
        if(this.value == 1){
            $('#formato-conteudo').html(language_array['file']);
            $('#div-cadastrados').find('input').attr('type', 'file');
            var select = $('#div-cadastrados').find('select');
            for (var i = 0; i < select.length; i++) {
                $(select[i]).select2("destroy");
            }
            select.remove();
            $('#loading-download').removeClass('hidden');
        }else{
            $('#loading-download').addClass('hidden');
            $('#div-cadastrados').find('input').attr('type', 'hidden');
            $('#formato-conteudo').html(language_array['content_registered']);
            var select = $('<select data-placeholder="'+ language_array['select']+'" id="conteudo_id" required="required" name="conteudo_id" class="form-control"   style="width: 100%;">');
            $('#div-cadastrados').append(select);
            var url = '//' + window.location.host + '/plataforma/library/index.php?action=list&origin=' + getOrigin();

            if($('#form_cad_arquivo').length > 0){
                url += '&type=2';
            }

            $('#div-cadastrados').find('select').select2({
                placeholder: language_array['select'],
                width: '100%',
                dropdownParent: $('#div-cadastrados'),
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.library,
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3,
                templateResult: function(item, li_element){
                    if(item.id > 0){
                        return item.text;
                    }
                }
            });
        }
    });

    $('#btn_form_cad_arquivo').click(function(e){

        var data = new FormData($('#form_cad_arquivo')[0]);
        var bar = '#loading-download';
        if($('select[name="options"]').val() == 2){
            bar = null;
        }else{
            var file = $('#arquivo');
            if(file.length > 0 && file[0].files[0].size > 576716800){
                alertElement (file, language_array['limit_max'] + ' 550MB');
                return;
            }
        }
        $(this).attr('old-text', $(this).text()).text(language_array['wait'] + '...').css('pointer-events', 'none');
        var ret = ajax($('#form_cad_arquivo').attr('action'), data, 'POST', null, false, false, bar, function(jsonReturn){
            if(typeof jsonReturn == 'string'){
                jsonReturn = JSON.parse(jsonReturn);
            }
            if(jsonReturn.error_code == -5 && bar){
                $('#loading-download').html('<div class="progress progress-striped active" style="margin:10px 0 0;"><div class="progress-bar progress-bar-danger text-center"  role="progressbar" style="width:100%"><b>'+language_array['extension_not_allowed']+'</b></div></div>');
            }
            validAjax($('#btn_form_cad_arquivo'), jsonReturn);
        });

    });

    $('#btn_form_comentario').click(function(e){
        $(this).attr('disabled', 'disabled');
        ajax($('#form_comentario').attr('action'), $('#form_comentario').serialize(), 'POST', 'json', null, null, null, function(jsonReturn){
            validAjax($('#btn_form_comentario'), jsonReturn, true);
            $('.elem-item').remove();
            $('.lesson_paginar').attr('limit', 0);
            listComments();
        });
    });

    $('#btn_form_responder_comentario').click(function(e){
        var btn = $(this);
        var m = btn.parents('.modal');
        $(this).attr('disabled', 'disabled');
        ajax($('#form_responder_comentario').attr('action'), $('#form_responder_comentario').serialize(), 'POST', 'json', null, null, null, function(jsonReturn){
            validAjax($('#btn_form_responder_comentario'), jsonReturn, true, m.find('.div_alert'));
            setTimeout(function(){
                m.modal('hide');
                $('.elem-item').remove();
                m.find('[name="responder"]').froalaEditor('html.set', '');
                $('.lesson_paginar').attr('limit', 0);
                listComments();
            }, 2000);
        });
    });

    $('#data_inicio').datetimepicker({
        language: 'pt-BR'
    });

    $('#formato-aula').hide();
    $('#tipo_aula').on('change', function() {
        if(this.value != ''){
            $('#formato-aula').show();
        }else{
            $('#formato-aula').hide();
        }
        
        $('#status').removeClass('hidden');

        if(this.value == 'sambatech' || this.value == 'soundcloud' || this.value == 'vimeo' || this.value == 'wistia' || this.value == 'storage' || this.value == 'storage2' || this.value == 'youtube' || this.value == 'mp3'){
            if($('.mod_aula').length > 0){
                $('#duracao-video').html('<div class="form-group"><label for="duracao" class="col-lg-2 control-label">'+ language_array['duration'] +'</label><div class="col-lg-3"><div id="aula-duracao" class="input-group"><input data-format="hh:mm:ss" type="text" name="duracao" id="duracao" maxlength="8" value="00:00:00" placeholder="00:00:00" class="form-control duracao" required="required"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div></div></div>');
            }else{
                $('#duracao-video').html('<div class="col-lg-3"><div class="form-group"><label for="duracao" class="">'+ language_array['duration'] +'</label><div id="aula-duracao" class="input-group"><input data-format="hh:mm:ss" type="text" name="duracao" id="duracao" maxlength="8" value="00:00:00" placeholder="00:00:00" class="form-control duracao" required="required"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div></div></div>');
            }
            $('#aula-duracao').datetimepicker({pickDate: false});
            $('#paginas-slide').html('');
            $('#div_data_inicio').addClass('hidden');
            $('#div_exibir_chat').addClass('hidden');
        }else if(this.value == 'pdf' || this.value == 'slideshare' || this.value == 'speakerdeck'){
            $('#div_data_inicio').addClass('hidden');
            $('#div_exibir_chat').addClass('hidden');
            if($('.mod_aula').length > 0){
                $('#paginas-slide').html('<div class="form-group"><label for="paginas" class="col-lg-2 control-label">'+ language_array['pages'] +'</label><div class="col-lg-2"><input type="number" name="paginas" id="paginas" min="1" maxlength="5" class="form-control" required="required"></div></div>');
            }else{
                $('#paginas-slide').html('<div class="col-lg-3"><div class="form-group"><label for="paginas">'+ language_array['pages'] +'</label><input type="number" name="paginas" id="paginas" min="1" maxlength="5" class="form-control" required="required"></div></div>');
            }
            $('#duracao-video').html('');
        }else if(this.value == 'hangouts' || this.value == 'editor' || this.value == 'cadastrados' 
                 || this.value == 'embed' || this.value == 'apprtc' || this.value == 'jitsi' 
                 || this.value == 'appearin' || this.value == 'live'){
            $('#aula-duracao').html('');
            $('#duracao-video').html('');
            $('#paginas-slide').html('');
            $('#div_data_inicio').addClass('hidden');
            $('#div_exibir_chat').addClass('hidden');
        }else if(this.value == 'livestream'){
            $('#aula-duracao').html('');
            $('#duracao-video').html('');
            $('#paginas-slide').html('');
            $('#div_data_inicio').addClass('hidden');
            $('#div_exibir_chat').addClass('hidden');
            //$('#status').addClass('hidden');
        }else if(this.value == 'vimeo_live' || this.value == 'youtube_live'){
            $('#aula-duracao').html('');
            $('#duracao-video').html('');
            $('#paginas-slide').html('');
            $('#div_data_inicio').removeClass('hidden');
            $('#div_exibir_chat').removeClass('hidden');
        }

        formatContent(this.value, $('#tipo-conteudo'), $('#div-conteudo'));
    });
    
    $('[name="demo"]').change(function(e){
        if(this.value == 1){
            $('.demolink').removeClass('hidden');
        }else{
            $('.demolink').addClass('hidden');
        }
    });

    var list = true;
    function listComments(){
        list = false;
        if($('.lesson_paginar').length > 0){
            var obj = {
                lesson_id: $('.lesson_paginar').attr('lessonid'),
                all: true,
                limit: $('.lesson_paginar').attr('limit')
            }

            var linnkdelete =  $('.lesson_paginar').attr('linnkdelete');
            var linkdeleteanswer = $('.lesson_paginar').attr('linkdeleteanswer');
            var template_row = $('.lesson_paginar').find('[template-row="comentario"]');
            var addComments = function(i, item){
                var new_row = template_row.clone();
                new_row.removeAttr('template-row')
                       .css('display', '')
                       .removeClass('hidden')
                       .addClass('elem-item')
                       .attr('id', 'c'+item.comentario_id);

                new_row.find('#user').attr('href', new_row.find('#user') .attr('urlpag') + 'p/' + item.username + '/')
                                     .attr('title', item.nome);

                new_row.find('img').attr('alt', item.nome).attr('src', (item.foto ? prepareImagesToView(item.foto_origin, 'profiles', 80, 80) : ' ' ));

                if(item.answers.length > 1){
                    new_row.find('[btnmoreanswer]').removeClass('hidden');
                }

                if(window.location.href.split('/').pop().indexOf('#') > -1 && window.location.href.split('/').pop() == '#c'+item.comentario_id ){
                    new_row.find('[btnmoreanswer]').addClass('hidden');
                    new_row.find('[btnlassanswer]').removeClass('hidden');
                    new_row.attr('open', true);
                    new_row.find('.answers.hidden').removeClass('hidden');
                    new_row.focus();
                }

                new_row.find('[btnmoreanswer]').css('cursor', 'pointer').click(function(){
                    new_row.find('[btnlassanswer]').removeClass('hidden');
                    new_row.find('[btnmoreanswer]').addClass('hidden');
                    new_row.find('.answers.hidden').removeClass('hidden');
                });

                new_row.find('[btnlassanswer]').css('cursor', 'pointer').click(function(){
                    new_row.find('[btnlassanswer]').addClass('hidden');
                    new_row.find('[btnmoreanswer]').removeClass('hidden');
                    new_row.find('.answers:not(:eq(0))').addClass('hidden');
                });

                var date = new formatStrToDate(item.data_comentario);
                var now = new formatStrToDate();

                if(date.getString('d/m/y') == now.getString('d/m/y')){
                    new_row.find('[datepublic]').text(date.getString('H:i:s'));
                }else{
                    new_row.find('[datepublic]').text(date.getString('d/m/y'));
                }

                new_row.find('[username]').text(item.nomecurto);
                var liked = false;
                $(item.studentslog).each(function(userkey, user){
                    if(user.aluno_id == user_id){
                        liked = true;
                    }
                    var a_user = $('<a href="'+ new_row.find('#user') .attr('urlpag') + 'p/' + user.username + '/">');
                    a_user.text(user.nomecurto + (userkey+1 == item.studentslog.length ? '' : ', ')).attr('title', user.nomecurto);
                    new_row.find('[divuserlikes]').append(a_user);
                });

                new_row.find('[countcomment]').text(item.pontuacao > 0 ? item.pontuacao : 0);
                new_row.find('[divcomment]').html(item.comentario);
                var sup_excluir = getUserPermission('suporte', 'excluir');
                new_row.find('[btndeletecomment]').unbind('click');
                if(item.aluno_id == new_row.attr('userid') || sup_excluir > 0){
                    new_row.find('[btndeletecomment]').click(function(e){
                        var obj = {
                            lesson_id: $('.lesson_paginar').attr('lessonid'),
                            course_id: item.loja_id,
                            comment_id: item.comentario_id
                        }

                        if(confirm(language_array['delete_comment'])){
                            ajax(linnkdelete+$('.lesson_paginar').attr('lessonid')+'/'+item.comentario_id, null, 'GET');
                            location.reload();
                        }
                    });
                }else{
                    new_row.find('[btndeletecomment]').remove();
                }

                if(liked){
                    new_row.find('.btn_form_like').css('cursor', 'default');
                }

                new_row.find('.btn_form_like').unbind('click');
                new_row.find('.btn_form_like').click(function(){
                    if(liked){
                        return;
                    }
                    var obj = {
                            aluno_votado: item.aluno_id,
                            comment_id: item.comentario_id
                        }
                    var count = $(this).find('.count');
                    count.html(parseInt(item.pontuacao) + 1);
                    ajax(new_row.find('#form_like').attr('action')+item.comentario_id+'/'+item.aluno_id, objSerialize(obj), 'GET', 'json', null, null, null, function(jsonReturn){
                        if(jsonReturn.error_code > 0){
                            
                        }
                    });
                });

                new_row.find('[btnanswercomment]').unbind('click');
                new_row.find('[btnanswercomment]').click(function(){
                    $('.modal [name="comment_id"]').val(item.comentario_id);
                });

                var template_rowA = new_row.find('[template-rowA]');

                var addAnswer = function(j, itemA){
                    var new_rowA = template_rowA.clone();
                    new_rowA.removeAttr('template-rowA')
                            .css('display', '')
                            .removeClass('hidden')
                            .addClass('answers')
                            .attr('id', 'a'+itemA.comentario_replica_id);
                    
                    if(j != 0){
                        new_rowA.addClass('hidden');
                    }

                    if(new_row.attr('open')){
                        new_row.find('.answers.hidden').removeClass('hidden');
                    }

                    if(window.location.href.split('/').pop().indexOf('#') > -1 && window.location.href.split('/').pop() == '#a'+itemA.comentario_replica_id ){
                        new_row.find('[btnmoreanswer]').addClass('hidden');
                        new_row.find('[btnlassanswer]').removeClass('hidden');
                        new_row.attr('open', true);
                        new_row.find('.answers.hidden').removeClass('hidden');
                        new_rowA.removeClass('hidden');
                    }

                    new_rowA.find('#userA').attr('href', new_rowA.find('#userA') .attr('urlpag') + 'p/' + itemA.username + '/')
                                     .attr('title', itemA.nome);

                    var date = new formatStrToDate(itemA.data_replica);
                    var now = new formatStrToDate();

                    new_rowA.find('img').attr('alt', itemA.nome).attr('src', (itemA.foto ? prepareImagesToView(itemA.foto_origin, 'profiles', 80, 80) : ' '));

                    if(date.getString('d/m/y') == now.getString('d/m/y')){
                        new_rowA.find('[dateApublic]').text(date.getString('H:i:s'));
                    }else{
                        new_rowA.find('[dateApublic]').text(date.getString('d/m/y'));
                    }
                    var liked = false;
                    $(itemA.studentslog).each(function(userkey, user){
                        if(user.aluno_id == user_id){
                            liked = true;
                        }
                        var a_user = $('<a href="'+ new_rowA.find('#userA').attr('urlpag') + 'p/' + user.username + '/">');
                        a_user.text(user.nomecurto + (userkey+1 == itemA.studentslog.length ? '' : ', ')).attr('title', user.nomecurto);
                        new_rowA.find('[divuserlikes-a]').append(a_user);
                    });

                    new_rowA.find('[username]').text(itemA.nomecurto);
                    new_rowA.find('[countA]').text(itemA.pontuacao_replica > 0 ? itemA.pontuacao_replica : 0);
                    new_rowA.find('[divcommentA]').html(itemA.resposta);
                    
                    var sup_excluir = getUserPermission('suporte', 'excluir');
                    new_rowA.find('[btndeletecommentA]').unbind('click');
                    if(itemA.aluno_id == new_row.attr('userid') || sup_excluir > 0){
                        new_rowA.find('[btndeletecommentA]').click(function(e){
                            var obj = {
                                lesson_id: $('.lesson_paginar').attr('lessonid'),
                                course_id: item.loja_id,
                                answer_id: itemA.comentario_replica_id
                            }

                            if(confirm(language_array['delete_answer'])){
                                ajax(linkdeleteanswer+$('.lesson_paginar').attr('lessonid')+'/'+item.comentario_id+'/'+itemA.comentario_replica_id, null, 'GET');
                                location.reload();
                            }
                        });
                    }else{
                        new_rowA.find('[btndeletecommentA]').remove();
                    }

                    if(liked){
                        new_rowA.find('.btn_form_like_replica').css('cursor', 'default');
                    }

                    new_rowA.find('.btn_form_like_replica').unbind('click');
                    new_rowA.find('.btn_form_like_replica').click(function(){
                        if(liked){
                            return;
                        }
                        var obj = {
                                aluno_votado: itemA.aluno_id,
                                answer_id: itemA.comentario_replica_id
                            }
                        var count = $(this).find('[countA]');
                        count.html(parseInt(itemA.pontuacao_replica) + 1);
                        ajax(new_row.find('#form_like_replica').attr('action')+item.comentario_id+'/'+itemA.comentario_replica_id+'/'+itemA.aluno_id, objSerialize(obj), 'GET', 'json', null, null, null, function(jsonReturn){
                            if(jsonReturn.error_code > 0){
                                
                            }
                        });
                    });


                    new_rowA.appendTo(new_row.find('[answers]'));
                }

                $(item.answers).each(function(j, itemA){
                    addAnswer(j, itemA);
                });

                if(item.answers.length < 2){
                    new_row.find('[btnmoreanswer]').addClass('hidden');
                    new_row.find('[btnlassanswer]').addClass('hidden');
                }

                new_row.appendTo($('.lesson_paginar'));
            }

            ajax($('.lesson_paginar').attr('action')+$('.lesson_paginar').attr('lessonid')+'/'+ true + '/' +$('.lesson_paginar').attr('limit'), null, 'GET', 'json', null, null, null, function(jsonReturn){
                $('.lesson_paginar').attr('limit', parseInt(obj.limit, 10)+5);
                if(jsonReturn && jsonReturn.comments.length > 0){
                    list = (jsonReturn.comments.length < 5 ? false : true);
                    $(jsonReturn.comments).each(function(i, item){
                        addComments(i, item);
                    });
                    $(window.location.href.split('/').pop()).focus();
                    var href = window.location.href.split('/').pop();
                    if(href.length > 0 && href.indexOf('#') > -1){
                        window.location.href = href;
                    }
                }
            });
        }
    }

    $(window).scroll(function() {
        var pos = parseInt($(window).scrollTop()/$(document).height()*100, 10);
        var cond1 = (pos > 69 && pos < 71 ? true : false);
        if(cond1 && list) {
            listComments();
        }
    });

    listComments();

    $('#table-aulas').dataTable({
        "order": [[1, "asc"], [2, "asc"]],
        "stateSave": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "../../../../plataforma/lesson/index.php?action=lessonPaginate&origin="+getOrigin()+"&course_id="+$('#table-aulas').attr('courseid'),
        columns: [
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
            { data: 6 },
            { data: 7 },
        ],
        "oLanguage": datable_language,
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            nRow = $(nRow);
            nRow.find('td')
                .addClass('val_md')
                .addClass('text-center');
            nRow.find('td:eq(1), td:eq(3), td:eq(4)').removeClass('text-center');

            if (aData[5]){
                var ms = aData[5].split(':');
                if (ms[0] == '00' && ms[1] == '00' ){
                    nRow.find('td:eq(5)').text('00:' + ms[2] + 'seg');
                }else if (ms[0] == '00'){
                    nRow.find('td:eq(5)').text(ms[1] + ':' +  ms[2] + 'min');
                } else {
                    nRow.find('td:eq(5)').text(ms[0] + ':' + ms[1] +  ':' + ms[2] + 'h');
                }
            }

            nRow.find('td:eq(1)').text(aData[10]);

            var status = '';
            
            if(aData[0] == 1){
                status = '<span data-tooltip="tooltip" title="'+language_array['published']+'">'+
                    '<i class="fa fa-check-circle"></i>'+
                '</span>';
            }else{
                status = '<span data-tooltip="tooltip" title="'+language_array['draft']+'">'+
                    '<i class="fa fa-clock-o"></i>'+
                '</span>';
            }

            nRow.find('td:eq(0)').html(status);

            var text = '';
            if(aData[12] > 0 && provas_modulo == 1 && isstarterClient == 0){
                text += '<i class="fa fa-list-ol" data-tooltip="tooltip" title="'+language_array['test']+'"></i> ';
            }

            if(aData[13] > 0 && isstarterClient == 0){
                text += '<i class="fa fa-check-square-o" data-tooltip="tooltip" title="'+language_array['quiz']+'"></i> ';
            }
            
            text += nRow.find('td:eq(4)').text();
            nRow.find('td:eq(4)').html(text);

            if (aData[8] > 0){
                nRow.find('td:eq(5)').text(aData[8] + ' pag');
            }

            if (aData[6]  == 'Embed' ){
                nRow.find('td:eq(5)').text(language_array['external_page']);
            }else if (aData[6]  == 'Html' ){
                nRow.find('td:eq(5)').text(language_array['html_page']);
            }else if (aData[6]  == 'Upx'){
                nRow.find('td:eq(5)').text(language_array['live_transimission']);
            }

            var options = [
                {
                    title: language_array['view_lesson'],
                    icon: '<i class="fa fa-eye"></i>',
                    click: function(){
                        window.location.href = $('table[trview]').attr('trview')+aData[7] + '/';
                    }
                },
                {
                    title: language_array['detail'],
                    icon: '<i class="fa fa-pencil"></i>',
                    click: function(){
                        window.location.href = $('table[trdetail]').attr('trdetail')+aData[7];
                    }
                },
            ];

            var professor_id = $('#table-aulas').attr('userid');
            var isTeacher = (aData[14] == 1);
            var p_provas = getUserPermission('cursos', 'aulas', 'provas_cad', 'ver');
            if(isbasicClient == true && provas_modulo == 1 && (p_provas == 2 || (p_provas == 1 && isTeacher))){
                options.push({
                    title: language_array['test'],
                    icon: '<i class="fa fa-list-ol"></i>',
                    click: function(){
                        window.location.href = $('table[trlinktest]').attr('trlinktest')+aData[7];
                    }
                });
            }

            var p_quiz = getUserPermission('cursos', 'aulas', 'quiz', 'ver');
            if(isstarterClient == 0){
                if(p_quiz == 2 || (p_quiz == 1 && isTeacher)){
                    options.push({
                        title: language_array['quiz'],
                        icon: '<i class="fa fa-check-square-o"></i>',
                        click: function(){
                            window.location.href = $('table[trquiz]').attr('trquiz')+aData[7];
                        }
                    });
                }
            }

            var p_arquivos = getUserPermission('cursos', 'aulas', 'arquivos', 'ver');
            if(p_arquivos == 2 || (p_arquivos == 1 && isTeacher)){
                options.push({
                    title: language_array['files'],
                    icon: '<i class="fa fa-paperclip"></i>',
                    click: function(){
                        window.location.href = $('table[trfiles]').attr('trfiles')+aData[7];
                    }
                });
            }

            var p_excluir = getUserPermission('cursos', 'aulas', 'excluir');
            if(p_excluir == 2 || (p_excluir == 1 && isTeacher)){
                options.push({
                    title: language_array['delete'],
                    icon: '<i class="fa fa-trash-o"></i>',
                    click: function(){
                        if (confirm(language_array['delete_lesson_message']  + '\r\r' + language_array['delete_lesson'])) {
                            window.location.href = $('table[trdelete]').attr('trdelete')+aData[7];
                        }
                    }
                });
            }
            
            nRow.find('td:eq(7)').remove();
            createOptions(nRow.append('<td class="val_md text-center">').find('td:eq(7)'), options);
        }
    });

    if(client_language != 1){
        $(".dataTables_filter input").attr("placeholder", "Search...");
    }

    if($('[disableAll]').length > 0){
        $('[disableAll]').find('button,input,select,textarea,a').attr('disabled', 'disabled');
        $('[disableAll]').find('button,input,select,textarea,a').unbind('click').attr('onclick', '');
        $('.editor').froalaEditor("edit.off");
    }

    $('.deleteVideo').click(function(){
        if(confirm("Deseja deletar este vídeo?")){
            window.location.href = $(this).attr('url')
        }
    });

    $('.publishVideo').click(function(e){
        if(confirm("Deseja realmente publicar este vídeo?\n\nCaso confirme, o vídeo levará alguns minutos para ficar disponível na aula.")){
            var url = $(this).attr('urlPublish');
           
            ajax(url, null, 'GET', 'json', null, null, null, function(jsonReturn){
               if(jsonReturn == true){
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            }); 
        }

        e.preventDefault();
        e.stopPropagation();
    });

    $('.draftVideo').click(function(e){
        if(confirm("Deseja realmente tornar este vídeo como rascunho?\n\nCaso confirme, o vídeo levará alguns minutos para ficar indisponível na aula.")){
            var urlP = $(this).attr('urlDraft');
           
            ajax(urlP, null, 'GET', 'json', null, null, null, function(jsonReturn){
               if(jsonReturn == true){
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            }); 
        }

        e.preventDefault();
        e.stopPropagation();
    });
});

function showUpload(url, vimeo_id){
    $('.hide-capa').hide();
    $('.upload-capa').html('<input type="file" name="capa" id="capa" accept=".jpg, .png, .gif" class="form-control"><p class="help-block text-left">' + language_array['send_img_jpg'] + ' 1280x720.</p><div id="loading-aula"></div><input type="hidden" name="url_video" value="'+ url +'"><input type="hidden" name="vimeo_id" value="' + vimeo_id + '">');
}

function showUpload2(url, videoId){
    $('.hide-capa').hide();
    $('.upload-capa').html('<input type="file" name="capavdo" id="capavdo" accept=".jpg, .png, .gif" class="form-control"><p class="help-block text-left">' + language_array['send_img_jpg'] + ' 1280x720.</p><div id="loading-aula"></div><input type="hidden" name="url_video" value="'+ url +'"><input type="hidden" name="vdoid" value="' + videoId + '">');
}

function showUploadLive(url){
    $('.hide-capa').hide();
    $('.upload-capa').html('<input type="file" name="capa_live" id="capa_live" accept=".jpg, .png, .gif" class="form-control"><p class="help-block text-left">' + language_array['send_img_jpg'] + '1920x1080.</p><div id="loading-aula-live"></div><input type="hidden" name="live_poster" value="'+ url +'">');
}
$(document).ready(function(){

    $('form input').keypress(function(e){ 
        if(e.which == 13){ 
            e.stopPropagation();
            e.preventDefault(); 
        } 
    });
    
    $(document).bind("keyup keydown", function(e){
        if(e.ctrlKey && e.keyCode == 80){
            return false;
        }
    });

    if($('[upxtypelesson]').length > 0){
        // install player with this one call
        $f("https://assets.eadplataforma.com/components/flowplayer/player.swf", {
            // configure player to use rtmp plugin
            clip: {
                provider: 'rtmp'
                ,live: true
                ,autoPlay: true
            },
            // here is our rtpm plugin configuration
            plugins: {
              rtmp: {
                   // use latest RTMP plugin release
                    url: 'flowplayer.rtmp-3.2.3.swf',
                    netConnectionUrl: 'rtmp://rtmp.cdn.upx.net.br:80/' + $('[upxtypelesson]').attr('upxtypelesson')
              }
            }
        });
    }

    var goFullScreen = function () {
        var fullscreenElement = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
        if(fullscreenElement){
            exitFullscreen();
        }else {
            launchIntoFullscreen(document.querySelector('.div-iframe'));
        }
    }

    var launchIntoFullscreen = function (element) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
    }

    // Whack fullscreen
    var exitFullscreen = function () {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }

    $('.btn-full').click(function(e){
        if($('.btn-remove-full').length > 0){
            goFullScreen();
            $('.btn-remove-full').removeClass('hidden');
            $('.btn-remove-full').click(function(){
                $('.btn-remove-full').addClass('hidden');
                exitFullscreen();
            });
        }else{
            var btn = $('<button type="button" class="btn-remove-full btn-custom"><i class="fa fa-arrows-alt"></i></button>');
            btn.click(function(e){
                $('.div-iframe').removeClass('full');
                $('body').removeClass('hidden-over');
                btn.remove();
            });

            $('body').append(btn);
            $('body').addClass('hidden-over');

            $('.aula-html').addClass('full');
            $('.div-iframe').addClass('full');
        }
    });

    var audio = $("#audio_with_controls");
    if(audio.length > 0){
        //$('body').append('<script src="'+url_source+'assets/js/mediaelement-and-player.js"></script>');
        var oReq = new XMLHttpRequest();
        oReq.open("GET", audio.find('source').attr("url"), true);
        oReq.responseType = "arraybuffer";
        oReq.onload = function(oEvent) {
            var blob = new Blob([oReq.response], {type: "audio/ogg"});
            var urlObj = (URL || webkitURL);
            var blobUrl = urlObj.createObjectURL(blob);
            audio.find('source').attr("src", blobUrl);
            audio[0].pause();
            audio[0].load();
            audio[0].currentTime = 0;
            //audio.mediaelementplayer({});
            //$('.mejs__container').css('margin', 'auto');
            audio[0].addEventListener("loadstart", function(){
                //urlObj.revokeObjectURL(blobUrl);
            });
        };
        oReq.send();
    }

    var lessonid = $('.timeincrement[lessonid]').attr('lessonid');
    var studentid = $('.timeincrement[studentid]').attr('studentid');
    var playerElem = $('#player');
    var playVideoStatus = false;
    var isPause = false;

    var clock = null;
    $('.timeincrement').timeCount(null, true);
    var clockincrement = $('.timeincrement').data('clock');
    if(clockincrement != null && $('#player').length != 0){
        clockincrement.pause = true;
    }

    var tipo = playerElem.attr('tipo');
    var uid = $('[u-id]').attr('u-id');
    var udv = $('[u-dv]').attr('u-dv');
    var eid = $('[e-id]').attr('e-id');
    
    var rd = null;
    if(tipo != 'eadplayer'){
        rd = (playerElem && playerElem.length > 0 ? new randomDiv(tipo, uid, udv, parseInt(eid, 10)) : null);
    }

    if(tipo == 'livestream'){
       if(rd){
            rd.start();
        } 
    }

    var onPlay = function(){
        playVideoStatus = true;
        isPause = false;
        if(clockincrement != null){
            clockincrement.pause = false;
        }
        if(clock != null){ 
            clock.pause = false;
        }

        if(rd){
            rd.start();
        }
    }

    var onPause = function(){
        playVideoStatus = false;
        isPause = true;
        if(clockincrement != null){
            clockincrement.pause = true;
        }
        if(clock != null){ 
            clock.pause = true;
        }

        if(rd){
            rd.stop();
        }
    }

    if(lessonid > 0){
        var focusNavegador = document.hasFocus();
        var focus = false;
        var doRequest = true;
        var player = null;
        var reload = false;
        var urlupdate = $('[updatelog]').attr('updatelog');
        var numView = parseInt($('[num]').attr('num'), 10);
        var permanencia = $('[permanencia]').attr('permanencia');
        var permanenciaOrigin = $('[permanenciaOrigin]').attr('permanenciaOrigin');
        var perSeconds = time_to_sec(permanencia);
        var valida_tempo = $('[valida_tempo]').attr('valida_tempo');
        var num_pause = parseInt($('[num_pause]').attr('num_pause'), 10);
        var count = 0;
        var timeStart = 0;
        var isStudent = $('[isStudent]').attr('isStudent');
        
        var timeFN = null;
        if($('[timestart]').length > 0){
            $('.timeLesson').timeCount(function(elem){
                if(!focus){
                    setTimeout(function(){
                        timeFN(true, true);
                    }, 1000);
                }
            });
            clock = $('.timeLesson').data('clock');
            clock.pause = true;
        }

        var nextLesson = function(){
            /*var btn = $('.comecar');
            if(btn.length > 0){
                var params = {
                    title: 'Avançar para a próxima aula em:',
                    text: '<span class="nextTime">5</span>',
                    fnLoad: function(modal_id, modal){
                        var span = modal.find('.nextTime');
                        var t = 5;
                        var interval = setInterval(function(){
                            if(t != 5){
                                span.text(t);
                            }
                            if(t == 0){
                                var href = btn.attr('href');
                                window.location.href = href;
                                clearInterval(interval);
                            }
                            t = t - 1;
                        }, 1000);
                    },
                    utilizaConfirm: true,
                    titleConfirm: 'Avançar Agora',
                    fnConfirm: function(){
                        var href = btn.attr('href');
                        window.location.href = href;
                    },
                    titleCancel: 'Cancelar',
                    fnCancel: function(){
                    },
                }
                alertModal(params);
            }*/
        }

        if(audio.length > 0){
            player = audio[0];
            playerElem = audio;
            tipo = 'audio';

            player.getCurrentTime = function(){
                return this.currentTime;
            }

            player.addEventListener("playing", function() {
                onPlay();
            });

            player.addEventListener("pause", function() {
                onPause();
            });

            player.addEventListener("ended", function() {
                nextLesson();
            });
        }

        var stopVideo = function(stop) {
            if((playerElem.length > 0 && valida_tempo == 1) || stop === true){
                if(tipo == 'youtube'){
                    if(player != null && typeof player.getCurrentTime != 'undefined'){
                        timeStart = player.getCurrentTime();
                        player.pauseVideo();
                    }
                }else if(tipo == 'wistia'){
                    if(player != null && typeof player.secondsWatched != 'undefined'){
                        timeStart = player.secondsWatched();
                        player.pause();
                    }
                }else if(tipo == 'storage'){
                    timeStart = player.getCurrentTime();
                    player.pause();
                }else if(tipo == 'vimeo'){
                    if(player != null && typeof player.getCurrentTime != 'undefined'){
                        player.getCurrentTime().then(function(seconds) {
                            timeStart = seconds;
                        });
                        player.pause();
                    }
                }else if(tipo == 'sambatech'){
                    player.getStatus(function(e){
                        timeStart = e.status.time;
                    });
                    player.pause();
                }else if(tipo == 'eadplayer'){
                    timeStart = player.currentTime;
                    player.pause();
                }else if(tipo == 'audio'){
                    timeStart = player.getCurrentTime();
                    player.pause();
                }
            }

            if(rd){
                rd.stop();
            }
        }

        var playVideo = function() {
            if(playerElem.length > 0 && valida_tempo == 1){
                if(tipo == 'youtube'){
                    if(player != null && typeof player.playVideo != 'undefined'){
                        //player.seekTo(timeStart);
                        player.playVideo();
                    }
                }else{
                    if(player != null && typeof player.play != 'undefined'){
                        //player.setCurrentTime(timeStart);
                        player.play();
                    }
                }
            }
            if(rd){
                rd.start();
            }
        }

        var timestart = null;
        var urlstatuslog = null;
        var time = null;
        var td_btn_next = null;
        if($('[timestart]').length > 0){
            timestart = $('[timestart]').attr('timestart');
            urlstatuslog = $('[urlstatuslog]').attr('urlstatuslog');
            time = $('[time]').attr('time');
            td_btn_next = $('.td_btn_next');
        }
        
        timeFN = function(notime, status){
            if($('[timestart]').length > 0){
                if(status){
                    var obj = {
                        lesson_id: lessonid,
                        tempo: clockincrement.time,
                        view: true 
                    }

                    clockincrement.seg = 0;
                    ajax(urlupdate, objSerialize(obj), 'POST', 'json', null, null, null, function(jsonReturn){
                        localStorage.setItem('timeLesson', null);
                        if(jsonReturn.block == 1){
                            location.reload();
                        }
                    });
                }
                var obj = {
                    lesson_id: lessonid, 
                }
                
                if(!notime){
                    obj.tempo = clock.time; 
                }

                ajax(urlstatuslog, objSerialize(obj), 'POST', 'json', null, null, null, function(returnJ){
                    localStorage.setItem('timeLesson', null);
                    if(returnJ.concluido == 1){
                        focus = true;
                        var link = $('<a href="" title="'+ language_array['next'] +' '+ language_array['lesson'] +'" class="comecar" data-tooltip="tooltip">'+
                                '<span class="hidden-xs">'+ language_array['next'] +'</span>&nbsp;'+
                                '<i class="fa fa-chevron-right"></i>'+
                            '</a>');
                        if(returnJ.linktotest && returnJ.linktotest.length > 0){
                            link.attr('href', returnJ.linktotest);
                        }else if(returnJ.lessonview && returnJ.lessonview.length > 0){
                            link.attr('href', returnJ.lessonview);
                        }else{
                            link = $('<a href="#depoimentoModal" data-toggle="modal" title="'+ language_array['rate'] +' '+ language_array['course'] +'" class="comecar" title="'+ language_array['give_opinion'] +'">' +
                                        '<i class="fa fa-thumbs-o-up"></i>&nbsp;' +
                                        '<span class="hidden-xs">'+ language_array['rate'] +'</span>' +
                                    '</a>');
                        }
                        td_btn_next.html('');
                        td_btn_next.append(link);
                    }
                });
            }
        }

        var rangeTime = null;
        if(num_pause){
            rangeTime = {};
            var secondsPartial = time_to_sec(permanenciaOrigin);
            var auxPartial = 0;
            if(numView > 0){
                for (var j = 0; j < numView; j++) {
                    for (var i = 0; i < num_pause; i++) {
                        rangeTime[parseInt(auxPartial+(Math.random() * secondsPartial), 10)] =  true;
                    }
                    auxPartial += secondsPartial;
                }
            }else{
                for (var i = 0; i < num_pause; i++) {
                    rangeTime[parseInt(auxPartial+(Math.random() * secondsPartial), 10)] =  true;
                }
                auxPartial += secondsPartial;
            }
        }

        var countInterval = 0;
        var intervalFocus = setInterval(function(timeinter){
            focusNavegador = document.hasFocus();
            var auxplayer = playVideoStatus;
            if(playVideoStatus){
                countInterval++;
            }

            if(auxplayer || !player){
                var objStorage = {
                    lesson_id: lessonid,
                    tempo: clockincrement.time,
                    student_id: studentid,
                    urlupdate: urlupdate,
                    
                }
                if(isStudent == 1){
                    objStorage.view = true;
                }
                localStorage.setItem('timeLesson', JSON.stringify(objStorage));
            }
            
            if(numView > 0 && doRequest && (focusNavegador || valida_tempo == 0) && isStudent == 1){
                if(playVideoStatus && player != null){
                    count++;
                    
                }else if(player == null){
                    count++;
                }
                if(perSeconds <= count){
                    var obj = {
                        lesson_id: lessonid,
                        tempo: clockincrement.time,
                        view: true 
                    }
                    ajax(urlupdate, objSerialize(obj), 'POST', 'json', null, null, null, function(jsonReturn){
                        localStorage.setItem('timeLesson', null);
                        if(jsonReturn.block == 1){
                            clearInterval(intervalFocus); 
                            location.reload();
                        }
                    });
                    return;
                }
            }

            if(!focusNavegador || isPause){
                if(valida_tempo == 1){
                    if(clock != null){
                        clock.pause = true;
                    }
                    clockincrement.pause = true;
                    
                    if(playVideoStatus || isPause){
                        stopVideo();
                    }
                }
                
                if(doRequest && (auxplayer || isPause || !player)){
                    doRequest = false;
                    var obj = {
                        lesson_id: lessonid,
                        tempo: clockincrement.time 
                    }
                    ajax(urlupdate, objSerialize(obj), 'POST', 'json', null, null, null, function(jsonReturn){
                        localStorage.setItem('timeLesson', null);
                        if(jsonReturn.block == 1){
                            reload = true;
                        }
                        clockincrement.seg = 0;
                        if(!focus){
                            timeFN();
                        }
                    });
                }
            }else if(playVideoStatus && player != null && rangeTime !== null){
                if(rangeTime[countInterval] === true){
                    stopVideo(true);
                }
                return;
            }else{
                if(reload && isStudent == 1){
                    location.reload();
                }
                if(clock != null && (auxplayer || !player)){
                    clock.pause = false;
                }
                
                if(auxplayer || !player){
                    clockincrement.pause = false;
                }

                if(!isPause){
                    if(!doRequest){
                        playVideo();
                    }
                    doRequest = true;
                }
            }
        }, 1000);
    }

    if(playerElem.length > 0 && tipo != 'audio'){
        var VideoID = playerElem.attr('url').split('?')[0].split('/').pop();
        if(tipo == 'youtube'){
            window.onYouTubePlayerAPIReady = function(){
                player = new YT.Player('player', {
                    height: '360',
                    width: '640',
                    videoId: VideoID,
                    playerVars: {
                        autoplay: 0,
                        rel: 0,
                        controls: 1,
                        showinfo: 0,
                        portrait: 0,
                        title: 0,
                        byline: 0,
                        autohide: 1,
                        color: hexaclient,
                        fs: 0
                    },
                    events: {
                        onReady: function (event) {
                            event.target.pauseVideo();
                        },
                        onStateChange: function (event) {
                            if (event.data == YT.PlayerState.PLAYING) {
                                onPlay();
                            }else{
                                onPause();
                            }

                            if(event.data == YT.PlayerState.ENDED){
                                nextLesson();
                            }
                        }
                    }
                });
            };
            $('body').append('<script src="https://www.youtube.com/iframe_api"></script>');

        } else if(tipo == 'sambatech'){
            var url = playerElem.attr('url');
            url = url.split('/');

            var player = new SambaPlayer("player", {
                height: 360,
                width: 640,
                ph: url[5],
                m: url[6],
                playerParams: { 
                    enableShare: false,
                },
                events: { 
                    "onStart": function(){
                        onPlay();
                    },
                    "onPause": function(){
                        onPause();
                    },
                    "onResume": function(){
                        onPlay();
                    },
                    "onFinish": function(){
                        onPause();
                        nextLesson();
                    },
                }
            });
        } else if(tipo == 'wistia'){
            playerElem.addClass('wistia_async_' + VideoID);
            window._wq = window._wq || [];
            _wq.push({ id: VideoID.substring(0, 3), onReady: function(video) {
                player = video;
                //player.time(timeStart);
                player.bind("play", function() {
                    onPlay();
                });

                player.bind("pause", function() {
                    onPause();
                });

                player.bind("end", function() {
                    onPause();
                    nextLesson();
                });

            }});
            
        }else if(tipo == 'vimeo'){
            player = new Vimeo.Player('player', {
                id: VideoID,
                width: 640,
                autoplay: false,
                autoplay: 0,
                rel: 0,
                controls: 1,
                showinfo: 0,
                portrait: 0,
                title: 0,
                byline: 0,
                autohide: 1
            });

            player.ready().then(function() {
                player.setColor(hexaclient).then(function(color) {
                    // the color that was set
                }).catch(function(error) {
                    // an error occurred setting the color
                });

                player.on('play', function() {
                    onPlay();
                });

                player.on('pause', function(){
                    onPause();
                });

                player.on('ended', function(){
                    onPause();
                    nextLesson();
                });
            }).catch(function(e){
                var iframe = document.querySelector('#player iframe');
                player = new Vimeo.Player(iframe);

                player.setColor(hexaclient).then(function(color) {
                    // the color that was set
                }).catch(function(error) {
                    // an error occurred setting the color
                });
            
                player.on('play', function() {
                    onPlay();
                });

                player.on('pause', function(){
                    onPause();
                });

                player.on('ended', function(){
                    onPause();
                    nextLesson();
                });
            });
        }else if(tipo == 'eadplayer'){
            ajax(playerElem.attr("get-url"), null, 'GET', 'json', null, null, null, function(jsonReturn){
                if(jsonReturn.otp){
                    player = getEadPlayer("#player", jsonReturn.otp, jsonReturn.playbackInfo);
                    player.fullscreen = false;
                    
                    setTimeout(function(){
                        $('.ytp-fullscreen-button.ytp-button').remove();
                        $('#player').children().css('padding-bottom', '56.25%');
                        $('.ytp-share-panel-title').text(language_array['wait_few_minutes']);
                        $('.ytp-share-panel-error').text(language_array['video_processing']);
                    }, 500);

                    player.addEventListener("load", function() {
                        $('.ytp-fullscreen-button.ytp-button').remove();
                        $('#player').children().css('padding-bottom', '56.25%');


                        $('.ytp-share-panel-title').text(language_array['wait_few_minutes']);
                        $('.ytp-share-panel-error').text(language_array['video_processing']);
                    });

                    player.addEventListener("play", function() {
                        $('.ytp-fullscreen-button.ytp-button').remove();
                        $('#player').children().css('padding-bottom', '56.25%');
                        onPlay();
                    });

                    player.addEventListener("pause", function() {
                        onPause();
                    });

                    player.addEventListener("ended", function() {
                        onPause();
                        nextLesson();
                    });
                }
            });
        }
    }

    var adaptChat = function(){
        var top = $('.video-ead').offset().top;
        var heightOri = $('.video_aula').height();
        var height = heightOri
        if(height > 350){
            //height = 350;
        }

        var topNew = top + (heightOri - height) - 7;
        var width = '360px';
        if($(window).width() < 768){
            width = '100%';
            topNew = top + height;
            //height = 450;
        }else{
            $('#chat-container').css({
                "width": '360px',
                "position": 'absolute', 
                "right": '0px',
            });

            $('.video_aula .container_video').css({ 
                "margin": 'inherit', 
            });
        }

        $('#chat-container').css({
            top: topNew + 'px',
            height: height + 'px',
            width: width
        });
    }

    $('.btn_chat').click(function(e){
        if($('#chat-container').hasClass('hf')){
            $('.btn_chat_text').text('Ocultar Chat');
            $('#chat-container').show('fade').removeClass('hf');
        }else{
            $('.btn_chat_text').text('Abrir Chat');
            $('#chat-container').hide('fade').addClass('hf');
        }
    });

    var aluno_id_per = $('#chat-container').attr('user_id');
    var aula_tipo = $('#chat-container').attr('tipo');
    var resolution = $('#chat-container').attr('resolution');

    //Tamanho video
    $('.tamanho').click(function(){
        var width = $(this).attr('data-width');
        $('.container_video').removeClass('tmh1 tmh2 tmh3').addClass(width);
        if(aula_tipo == 14 || aula_tipo == 15 || aula_tipo == 16){
            adaptChat();
        }
        ajax($('.container_video').attr('action') + width, null, 'GET');
    });

    $(window).resize(function() {
        if(aula_tipo == 14 || aula_tipo == 15 || aula_tipo == 16){
            adaptChat();
        }
    });

    if(aula_tipo == 14 || aula_tipo == 15 || aula_tipo == 16){    
        $('[data-width="' + resolution + '"]').trigger('click');
    }

    var getElemFullscreen = function(){
        var fullElem = null;
        if(document.fullscreenElement && document.fullscreenElement !== null){
            fullElem = document.fullscreenElement;
        }else if (document.webkitFullscreenElement && document.webkitFullscreenElement !== null){
            fullElem = document.webkitFullscreenElement;
        }else if(document.mozFullScreenElement && document.mozFullScreenElement !== null){
            fullElem = document.mozFullScreenElement;
        }else if (document.msFullscreenElement && document.msFullscreenElement !== null){
            fullElem = document.msFullscreenElement;
        }
        return $(fullElem);
    }

    var closeFullscreen = function (fullElem) {
        if(!fullElem.hasClass('div-iframe')){
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }

    if($(".aula-html").length == 0 && $('[isUrl]').length == 0){
        var intFull = setInterval(function(){
            var fullElem = getElemFullscreen();
            if(fullElem.length > 0){
                closeFullscreen(fullElem);
            }
        }, 500);
    }

    $('.btn_choose').click(function(){
        window.location.href = $(this).attr('url');
    });
});
