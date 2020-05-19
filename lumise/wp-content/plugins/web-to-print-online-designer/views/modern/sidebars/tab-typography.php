<div class="tab <?php if( $active_typos ) echo 'active'; ?> " ng-if="settings['nbdesigner_enable_text'] == 'yes'" id="tab-typography" nbd-scroll="scrollLoadMore(container, type)" data-container="#tab-typography" data-type="typography" data-offset="20">
    <div class="tab-main tab-scroll">
        <div class="typography-head">
            <span class="text-guide" style="color: #4F5467; margin-bottom: 20px;display: block;"><?php _e('Click to add text','web-to-print-online-designer'); ?></span>
            <div class="head-main">
                <span class="text-heading" ng-click='addText("<?php _e('Company Name','web-to-print-online-designer'); ?>", "heading")' style="color: #4F5467; display: block; font-size: 42px; font-weight: 700"><?php _e('Add heading','web-to-print-online-designer'); ?></span>
                <span class="text-sub-heading" ng-click="addText('<?php _e('Subheading','web-to-print-online-designer');?>', 'subheading')" style="display: block; font-size: 30px; font-weight: 500; color: #4F5467"><?php _e('Add subheading','web-to-print-online-designer');?></span>
                <span ng-click="addText('<?php _e('Add a little bit of body text','web-to-print-online-designer'); ?>')" class="text-body" style="display: block;color: #4F5467; font-size: 16px;"><?php _e('Add a little bit of body text','web-to-print-online-designer'); ?></span>
                <span ng-click="addCurvedText('<?php _e('Curved text','web-to-print-online-designer'); ?>')" class="text-body" style="display: block;color: #4F5467; font-size: 16px;"><?php _e('Add curved text','web-to-print-online-designer'); ?></span>
            </div>
        </div>
<!--        <hr ng-show="isTemplateMode" style="border-top: 1px solid rgba(255,255,255,0.5);margin: 0 10px 20px;"/>
        <div ng-show="isTemplateMode" class="user-infos" style="text-align: left; margin-bottom: 20px;">
            <p style="margin-left: 7px;"><?php //_e('Business card','web-to-print-online-designer'); ?></p>
            <button ng-repeat="(iIndex, info) in settings.user_infos" ng-click="addUserInfo(iIndex)" class="nbd-button">{{info.title}}</button>
            <button ng-click="buildVcart()" class="nbd-button"><?php //_e('Vcard','web-to-print-online-designer'); ?></button>
            <hr style="border-top: 1px solid rgba(255,255,255,0.5);margin: 0 10px 20px;"/>
            <p style="margin-left: 7px;"><?php //_e('Contact sheet','web-to-print-online-designer'); ?></p>
            
            <div style="margin-left: 7px;">
                <select ng-change="getUsersByCountryCode()" ng-model="currentCountryCode">
                    <option value=""><?php //_e('Choose contact:','web-to-print-online-designer'); ?></option>
                    <option value="VN">VN</option>
                    <option value="US">US</option>
                </select>
                <div ng-repeat="i in [0,1,2,3,4,5,6,7]" ng-show="contacts.length > 0">
                    <div><label style="margin-top: 10px; margin-bottom: 0;" for="contact-{{i}}"><?php //_e('Contact:','web-to-print-online-designer'); ?></label></div>
                    <select id="contact-{{i}}" ng-model="_contacts[i]" ng-change="_changeContact(i)">
                        <option value=""><?php //_e('Choose contact:','web-to-print-online-designer'); ?></option>
                        <option ng-repeat="(contact_index, contact) in contacts" value="{{contact_index}}">{{contact.c_full_name}}</option>
                    </select>
                </div>
                <div style="margin-top: 15px;"><button class="nbd-button" ng-click="updateTemplate()"><?php //_e('Update','web-to-print-online-designer'); ?></button></div>
            </div>
            
            <button ng-if="index != 'contact' && index != 'currentContactIndex' && index != 'contactsheet' && index != 'first_con'" ng-repeat="(index, info) in settings.contact_sheets" ng-click="addContactSheet(index)" class="nbd-button">{{index}}</button>
            <p style="margin-left: 7px;"><?php //_e('Choose contact','web-to-print-online-designer'); ?></p>
            <select style="margin-left: 7px;" ng-model="settings.contact_sheets.first_con" ng-change="changeContact()">
                <option ng-repeat="con in settings.contact_sheets.contactsheet" value="{{con}}">{{con}}</option>
            </select>
            <p style="margin-left: 7px;"><?php //_e('Related Contacts','web-to-print-online-designer'); ?></p>
            <select convert-to-number style="margin-left: 7px;" ng-model="settings.contact_sheets.currentContactIndex">
                <option ng-repeat="(conIndex, _con) in settings.contact_sheets.contact[settings.contact_sheets.first_con]" value="{{conIndex}}">{{_con.c_full_name}}</option>
            </select><br />
            <button ng-repeat="(cIndex, c) in settings.contact_sheet[settings.contact_sheets.currentContactIndex]" ng-click="addContactSheet('contact', cIndex)" class="nbd-button">{{cIndex}}: {{c}}</button>
        </div>-->
        <hr ng-if="settings.nbdesigner_hide_typo_section == 'no'" style="border-top: 1px solid rgba(255,255,255,0.5);margin: 0 10px 20px;"/>
        <div ng-if="settings.nbdesigner_hide_typo_section == 'no'" class="typography-body">
            <ul class="typography-items">
                <li nbd-drag="typo.folder" type="typo" ng-click="insertTypography(typo)" class="typography-item" ng-repeat="typo in resource.typography.data | limitTo: resource.typography.filter.currentPage * resource.typography.filter.perPage" repeat-end="onEndRepeat('typography')">
                    <img ng-src="{{generateTypoLink(typo)}}" alt="Typography" />
                </li>
            </ul>
            <div class="loading-photo" style="width: 40px; height: 40px;">
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                </svg>
            </div>                
        </div>
    </div>
</div>