 <div class="row">
   <div class="col-md-12 text-center">
     <div class="profile">
       <div class="profile-header">
         <div class="profile-header-cover"></div>
         <div class="profile-header-content">
           <div class="profile-header-img d-block mx-auto">
             <img src="<?php echo STORAGE_URL; ?>/users/img/profile/<?php echo FETCH($PageSqls, "UserProfileImage"); ?>" alt="" />
           </div>
           <div class="profile-header-info">
             <h4 class="mt-0 mb-1 text-center"><?php echo FETCH($PageSqls, "UserSalutation"); ?> <?php echo FETCH($PageSqls, "UserFullName"); ?></h4>
             <p class="mt-0 display-6">
               <a href="&?send_mail_to=<?php echo FETCH($PageSqls, "UserEmailId"); ?>" class="text-primary"><i class="fa fa-envelope"></i> <?php echo FETCH($PageSqls, "UserEmailId"); ?></a><br>
               <a href="tel=<?php echo FETCH($PageSqls, "UserPhoneNumber"); ?>" class="text-primary"><i class="fa fa-phone-square"></i> <?php echo FETCH($PageSqls, "UserPhoneNumber"); ?></a><br>
             </p>
             <?php
              $GetAddress = FETCH_TABLE_FROM_DB("SELECT * FROM user_addresses WHERE UserAddressUserId='$REQ_UserId'", true);
              if ($GetAddress != null) {
                foreach ($GetAddress as $Address) { ?>
                 <p class="flex-s-b mb-0">
                   <span class="info-details">
                     <b><?php echo SECURE($Address->UserAddressType, "d"); ?></b><br>
                     <span>
                       <?php echo SECURE($Address->UserStreetAddress, "d"); ?>
                       <?php echo SECURE($Address->UserLocality, "d"); ?>
                       <?php echo SECURE($Address->UserCity, "d"); ?>
                       <?php echo SECURE($Address->UserState, "d"); ?>
                       <?php echo SECURE($Address->UserCountry, "d"); ?>
                       <?php echo SECURE($Address->UserPincode, "d"); ?>
                       <?php echo SECURE($Address->UserPincode, "d"); ?><br>
                       <?php echo SECURE($Address->UserAddressContactPerson, "d"); ?><br>
                       <?php echo SECURE($Address->UserAddressNotes, "d"); ?><br>
                       <a href=" <?php echo SECURE($Address->UserAddressMapUrl, "d"); ?>" class="btn btn-sm btn-success">View Location On Map</a>
                     </span>
                   </span>
                 </p>
             <?php }
              } ?>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>