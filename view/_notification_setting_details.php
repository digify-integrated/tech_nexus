<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Notification Setting</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                 
              if ($notificationSettingWriteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-notification-channel-offcanvas" aria-controls="update-notification-channel-offcanvas" id="update-notification-channel">Notification Channel</button></li>
                <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-system-notification-template-offcanvas" aria-controls="update-system-notification-template-offcanvas" id="update-system-notification-template">System Notification Template</button></li>
                <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-email-notification-template-offcanvas" aria-controls="update-email-notification-template-offcanvas" id="update-email-notification-template">Email Notification Template</button></li>
                <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-sms-notification-template-offcanvas" aria-controls="update-sms-notification-template-offcanvas" id="update-sms-notification-template">SMS Notification Template</button></li>';
              }
                 
              if ($notificationSettingDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-notification-setting">Duplicate Notification Setting</button></li>';
              }
                        
              if ($notificationSettingDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-notification-setting-details">Delete Notification Setting</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($notificationSettingWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="notification-setting-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($notificationSettingCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="notification-setting.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="notification-setting-form" method="post" action="#">
          <?php
            if($notificationSettingWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="notification_setting_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="notification_setting_name" name="notification_setting_name" maxlength="100" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="notification_setting_description_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="notification_setting_description" name="notification_setting_description" maxlength="200" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="notification_setting_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Description</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="notification_setting_description_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
<?php
  echo '<div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>Log Notes</h5>
                </div>
              </div>
            </div>
            <div class="log-notes-scroll" style="max-height: 450px; position: relative;">
              <div class="card-body p-b-0">
                '. $userModel->generateLogNotes('notification_setting', $notificationSettingID) .'
              </div>
            </div>
          </div>
        </div>';
    
    if($notificationSettingWriteAccess['total'] > 0){
      echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="update-notification-channel-offcanvas" aria-labelledby="update-notification-channel-offcanvas-label">
              <div class="offcanvas-header">
                <h2 id="update-notification-channel-offcanvas-label" style="margin-bottom:-0.5rem">Notification Channel</h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="alert alert-success alert-dismissible mb-4" role="alert">
                  A notification channel table designed to select and document the preferred communication channels for sending notifications or messages within a system or organization.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <table id="update-notification-channel-table" class="table table-striped table-hover table-bordered nowrap w-100">
                      <thead>
                        <tr>
                          <th>Notification Channel</th>
                          <th>Assign</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="update-system-notification-template-offcanvas" aria-labelledby="update-system-notification-template-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="update-system-notification-template-offcanvas-label" style="margin-bottom:-0.5rem">System Notification Template</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                This system notification template is used to modify and customize the content and format of system notifications sent to recipients for various purposes, ensuring effective communication and engagement.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <form id="update-system-notification-template-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="system_notification_title">Title <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="system_notification_title" name="system_notification_title" maxlength="200" autocomplete="off">
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="system_notification_message">Message <span class="text-danger">*</span></label>
                      <textarea class="form-control" id="system_notification_message" name="system_notification_message" maxlength="200" rows="5"></textarea>
                    </div>
                  </form>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-update-system-notification-template" form="update-system-notification-template-form">Submit</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
              </div>
            </div>
          </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="update-email-notification-template-offcanvas" aria-labelledby="update-email-notification-template-offcanvas-label">
          <div class="offcanvas-header">
            <h2 id="update-email-notification-template-offcanvas-label" style="margin-bottom:-0.5rem">Email Notification Template</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <div class="alert alert-success alert-dismissible mb-4" role="alert">
              This email notification template is used to modify and customize the content and format of email notifications sent to recipients for various purposes, ensuring effective communication and engagement.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <form id="update-email-notification-template-form" method="post" action="#">
                  <div class="form-group">
                    <label class="form-label" for="email_notification_subject">Subject <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="email_notification_subject" name="email_notification_subject" maxlength="200" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="email_notification_body">Body <span class="text-danger">*</span></label>
                    <textarea id="email_notification_body" name="email_notification_body" class="tox-target tiny-mce"></textarea>
                  </div>
                </form>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <button type="submit" class="btn btn-primary" id="submit-update-email-notification-template" form="update-email-notification-template-form">Submit</button>
              <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="update-sms-notification-template-offcanvas" aria-labelledby="update-sms-notification-template-offcanvas-label">
        <div class="offcanvas-header">
          <h2 id="update-sms-notification-template-offcanvas-label" style="margin-bottom:-0.5rem">SMS Notification Template</h2>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <div class="alert alert-success alert-dismissible mb-4" role="alert">
            This SMS notification template is used to modify and customize the content and format of SMS notifications sent to recipients for various purposes, ensuring effective communication and engagement.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <form id="update-sms-notification-template-form" method="post" action="#">
                <div class="form-group">
                  <label class="form-label" for="sms_notification_message">Message <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="sms_notification_message" name="sms_notification_message" maxlength="500" rows="5"></textarea>
                </div>
              </form>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <button type="submit" class="btn btn-primary" id="submit-update-sms-notification-template" form="update-sms-notification-template-form">Submit</button>
            <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
          </div>
        </div>
      </div>
    </div>';
    }

?>
</div>