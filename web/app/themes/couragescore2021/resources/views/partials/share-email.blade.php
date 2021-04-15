
<script type="text/javascript">
    actionkit.forms.contextRoot = 'https://act.couragecampaign.org/context/';
     actionkit.forms.initForm('act')
</script>

@php
    $score = App\get_score($post);
    $color = App\get_color($score);
    $letter = App\get_letter($score);

    //Not sure what this does
    $thank_you_message = '' //$_GET['action_id'];
@endphp

<div id="contact-form" class="contact-form" <?php if($score == 'na'){echo 'style="display:none"';}?>>
	@if(!$thank_you_message)
	<h3 class="title"><?php _e('Contact', 'progressive');?> <?php the_title();?></h3>
	<div class="p-single__form">
		<form class="ak-form" name="act" method="POST" action="https://act.couragecampaign.org/act/" accept-charset="utf-8" target="_self">
			<input type="hidden" name="page" value="<?php echo get_post_field( 'post_name', $pers_id) ?>">
			<div class="ak-grid-row">
				<div class="ak-grid-col ak-grid-col-5-of-12">
					<div class="statement-text-wrapper ak-field-box ak-field-box-borderless">
						<div id="petition-form" class="ak-styled-fields ak-labels-overlaid ak-errs-below">
							<ul class="compact" id="ak-errors"></ul>
							<div id="unknown_user" class="ak-user-form">
								<div class="row">
									<div id="ak-fieldbox-first_name " class="col-xs-6 required ">
										<input type="text" name="first_name" id="id_first_name" class="ak-userfield-input" placeholder="<?php _e('First name', 'progressive');?>">
									</div>
									<div id="ak-fieldbox-last_name " class="col-xs-6 required ">
										<input type="text" name="last_name" id="id_last_name" class="ak-userfield-input" placeholder="<?php _e('Last name', 'progressive');?>">
									</div>
								</div>
								<div class="row">
									<div id="ak-fieldbox-phone "  class="col-xs-6">
										<input type="text" name="phone" id="id_phone" class="ak-userfield-input" placeholder="<?php _e('Phone', 'progressive');?>">
									</div>
									<div id="ak-fieldbox-user_zip " class="col-xs-6 ak-err-below">
										<input type="text" name="user_zip" id="id_user_zip" class="ak-userfield-input ak-has-overlay" placeholder="<?php _e('Zip', 'progressive');?>">
									</div>
									</div>
								<div class="row">
									<div id="ak-fieldbox-email" class="col-xs-12 required ">
										<input type="text" name="email" id="id_email" class="ak-userfield-input" placeholder="<?php _e('Email', 'progressive');?>">
									</div>
								</div>

										@switch($letter)
										    @case('A+')
											@php $preff = get_field('score_a+', 'option') @endphp
											@break
										@case('A')
                                            @php $preff = get_field('score_a', 'option') @endphp
											@break
										@case('B')
                                            @php $preff = get_field('score_b', 'option') @endphp
											@break
										@case('C')
                                            @php $preff = get_field('score_c', 'option') @endphp
											@break
										@case('D')
                                            @php $preff = get_field('score_d', 'option') @endphp
											@break
										@case('F')
											@php $preff = get_field('score_f', 'option') @endphp
											@break
                                        @endswitch

								<div class="row">
									<div id="ak-fieldbox-user_custom_message" class="col-xs-12">
										<div class="text"><?php echo $preff;?></div>
										<textarea name="action_rating_message" id="id_action_rating_message" class="ak-userfield-input ak-has-overlay"><?php echo $preff;?></textarea>
									</div>
								</div>
								<input type="hidden" name="country" value="United States">
							</div>
							<div class="row">
								<div class="col-xs-12 ak-field">
									<div class="textarea">
										<textarea id="id_comment" name="action_comment" placeholder="<?php _e('Add your own message.....', 'progressive');?>"></textarea>
										<!-- <div class="api">ActionKit  API</div> -->
									</div>
								</div>
							</div>
							<div class="cf">
								<!-- <input type="submit" value="SEND"> -->
								<button type="submit" class="ak-submit-button"><?php _e('SEND', 'progressive');?></button>
							</div>
							<div class="add-note cf">
							<p><?php _e('NOTE: Although you may be disappointed with your representative, please be respectful. Use this opportunity to offer constructive feedback. Please abstain from negative, disparaging language, including, but not limited to: expletives, comments about race, gender identity, sexual orientation, ethnicity or religion, and anything specific to appearance.', 'progressive');?></p>
							</div>
						</div>
					</div>  
				</div>
			</div><!--gridrow-->
		</form>
		{!! the_field('form_code') !!}
	</div>
	@else
	<script>
        jQuery.fancybox.open({
            src  : '#contact-form',
            type : 'inline',
        });
        jQuery('.socials #contact-form-btn').click(function(e){
            e.preventDefault();
            jQuery.fancybox.open({
                src  : '#contact-form',
                type : 'inline',
                opts : {
                    afterShow : function( instance, current ) {
                        console.info( 'done!' );
                        actionkit.forms.initPage();
                        actionkit.forms.contextRoot = 'https://act.couragecampaign.org/context/';
                        actionkit.forms.initForm('act')
                    }
                }
            });
        });	
	</script>

	<h3 class="title"><?php _e('Thank you!', 'progressive');?></h3>
	<div class="p-single__form">
		<div class="ak-form">
			<div id="ak-fieldbox-user_custom_message" class="col-xs-12">
				<div class="text" style="display: block; padding: 20px;"><?php _e('Thank you for sharing your thoughts with your legislator. Close this window to return to <a href="http://www.couragescore.org">CourageScore.org', 'progressive');?></div>
			</div>
		</div>

	</div>
	@endif
</div>