<div class="col-md-12">
   <div class="p-1 mb-1 shadow-sm rounded-2 bg-white data-list">
      <p class="mb-0">
         <span>
            <?php echo $Sno; ?>
         </span> |
         <span>
            <a href="details/?uid=<?php echo SECURE($Customers->UserId, "e"); ?>" class="text-primary bold">
               <img src="<?php echo STORAGE_URL; ?>/users/img/profile/<?php echo $Customers->UserProfileImage; ?>" class="img-fluid user-list-icon">
               <span class="text-grey"> <b><?php echo $Customers->UserSalutation; ?></span> <?php echo $Customers->UserFullName; ?></b>
            </a>
         </span> |
         <span>
            <a href="tel:<?php echo $Customers->UserPhoneNumber; ?>">
               <i class="fa fa-phone-square text-primary"></i> <?php echo $Customers->UserPhoneNumber; ?>
            </a>
         </span> |
         <span>
            <a href="mailto:<?php echo $Customers->UserEmailId; ?>">
               <i class="fa fa-envelope text-danger"></i> <?php echo $Customers->UserEmailId; ?>
            </a>
         </span> |
         <span>
            <b><i class="fa fa-cake-candles text-danger"></i></b> <?php echo DATE_FORMATE("d M, Y", $Customers->UserDateOfBirth); ?>
         </span> |
         <span>
            <span>
               <?php echo StatusViewWithText($Customers->UserStatus); ?>
            </span>

            <?php
            $Check = CHECK("SELECT * FROM leads where LeadPersonManagedBy='" . $Customers->UserId . "'");
            if ($Check == null) {
               CONFIRM_DELETE_POPUP(
                  "remove_users",
                  [
                     "remove_team_member" => true,
                     "control_id" => $Customers->UserId,
                  ],
                  "usercontroller",
                  "Remove",
                  "text-danger pull-right"
               );
            } ?>
         </span>
      </p>
   </div>
</div>