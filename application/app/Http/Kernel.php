<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [

            //[growcrm] make sure we have no session during setup
            \App\Http\Middleware\General\Setup::class,

            //system middleware
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            //[growcrm] [settings middleware]
            \App\Http\Middleware\General\Settings::class,
            //[growcrm] [general middleware]
            \App\Http\Middleware\General\SanityCheck::class,
            //[growcrm] [general middleware]
            \App\Http\Middleware\General\General::class,
            //[growcrm] [general middleware]
            \App\Http\Middleware\General\General::class,
            //[growcrm] [modules middleware]
            \App\Http\Middleware\Modules\Status::class,
            //[growcrm] [modules middleware]
            \App\Http\Middleware\Modules\Visibility::class,

            //[MODULES] [growcrm] [modules main menus]
            \App\Http\Middleware\Modules\Menus::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [

        //system
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'memo' => \App\Http\Middleware\General\Memo::class, //[memo]

        /** ---------------------------------------------------------------------------------
         * [SAAS] MIDDLEWARE
         *-----------------------------------------------------------------------------------*/
        'accountStatus' => \App\Http\Middleware\Account\AccountStatus::class,
        'accountLimitsClients' => \App\Http\Middleware\Account\AccountLimitsClients::class,
        'accountLimitsTeam' => \App\Http\Middleware\Account\AccountLimitsTeam::class,
        'accountLimitsProjects' => \App\Http\Middleware\Account\AccountLimitsProjects::class,

        /** ---------------------------------------------------------------------------------
         * CRM MIDDLEWARE
         *-----------------------------------------------------------------------------------*/

        //[growcrm] - [general]
        'adminCheck' => \App\Http\Middleware\General\AdminCheck::class,
        'teamCheck' => \App\Http\Middleware\General\TeamCheck::class,
        'generalMiddleware' => \App\Http\Middleware\General\General::class,
        'demoModeCheck' => \App\Http\Middleware\General\DemoCheck::class,
        'FileSecurityCheck' => \App\Http\Middleware\Fileupload\FileSecurityCheck::class,

        //[growcrm] - [authentication]
        'authenticationMiddlewareGeneral' => \App\Http\Middleware\Authenticate\General::class,

        //[growcrm] - [authentication]
        'categoriesMiddlewareGeneral' => \App\Http\Middleware\Categories\General::class,

        //[growcrm] - [clients]
        'clientsMiddlewareIndex' => \App\Http\Middleware\Clients\Index::class,
        'clientsMiddlewareEdit' => \App\Http\Middleware\Clients\Edit::class,
        'clientsMiddlewareDestroy' => \App\Http\Middleware\Clients\Destroy::class,
        'clientsMiddlewareShow' => \App\Http\Middleware\Clients\Show::class,
        'clientsMiddlewareCreate' => \App\Http\Middleware\Clients\Create::class,
        'importClientsMiddlewareCreate' => \App\Http\Middleware\Import\Clients\Create::class,

        //[growcrm] - [projects]
        'projectsMiddlewareIndex' => \App\Http\Middleware\Projects\Index::class,
        'projectsMiddlewareShow' => \App\Http\Middleware\Projects\Show::class,
        'projectsMiddlewareEdit' => \App\Http\Middleware\Projects\Edit::class,
        'projectsMiddlewareCreate' => \App\Http\Middleware\Projects\Create::class,
        'projectsMiddlewareDestroy' => \App\Http\Middleware\Projects\Destroy::class,
        'projectsMiddlewareBulkEdit' => \App\Http\Middleware\Projects\BulkEdit::class,
        'projectsMiddlewareBulkAssign' => \App\Http\Middleware\Projects\BulkAssign::class,

        //[growcrm] - [knowledgebase]
        'knowledgebaseMiddlewareIndex' => \App\Http\Middleware\Knowledgebase\Index::class,
        'knowledgebaseMiddlewareCreate' => \App\Http\Middleware\Knowledgebase\Create::class,
        'knowledgebaseMiddlewareEdit' => \App\Http\Middleware\Knowledgebase\Edit::class,
        'knowledgebaseMiddlewareDestroy' => \App\Http\Middleware\Knowledgebase\Destroy::class,
        'knowledgebaseMiddlewareShow' => \App\Http\Middleware\Knowledgebase\Show::class,

        //[growcrm] - [knowledgebase]
        'knowledgebaseCategoriesMiddlewareEdit' => \App\Http\Middleware\Kbcategories\Edit::class,
        'knowledgebaseCategoriesMiddlewareDestroy' => \App\Http\Middleware\Kbcategories\Destroy::class,

        //[growcrm] - [timesheets]
        'timesheetsMiddlewareIndex' => \App\Http\Middleware\Timesheets\Index::class,
        'timesheetsMiddlewareDestroy' => \App\Http\Middleware\Timesheets\Destroy::class,
        'timesheetsMiddlewareEdit' => \App\Http\Middleware\Timesheets\Edit::class,

        //[growcrm] - [attendances]
        'attendancesMiddlewareIndex' => \App\Http\Middleware\Attendances\Index::class,
        'attendancesMiddlewareCreate' => \App\Http\Middleware\Attendances\Create::class,
        'attendancesMiddlewareDestroy' => \App\Http\Middleware\Attendances\Destroy::class,
        'attendancesMiddlewareEdit' => \App\Http\Middleware\Attendances\Edit::class,
        'importAttendancesMiddlewareCreate' => \App\Http\Middleware\Import\Attendances\Create::class,

        //[growcrm] - [holidays]
        'holidaysMiddlewareIndex' => \App\Http\Middleware\Holidays\Index::class,
        'holidaysMiddlewareCreate' => \App\Http\Middleware\Holidays\Create::class,
        'holidaysMiddlewareDestroy' => \App\Http\Middleware\Holidays\Destroy::class,
        'holidaysMiddlewareEdit' => \App\Http\Middleware\Holidays\Edit::class,

        //[growcrm] - [leaves]
        'leavesMiddlewareIndex' => \App\Http\Middleware\Leaves\Index::class,
        'leavesMiddlewareCreate' => \App\Http\Middleware\Leaves\Create::class,
        'leavesMiddlewareDestroy' => \App\Http\Middleware\Leaves\Destroy::class,
        'leavesMiddlewareEdit' => \App\Http\Middleware\Leaves\Edit::class,

        //[growcrm] - [settings]
        'settingsMiddlewareIndex' => \App\Http\Middleware\Settings\Index::class,

        //[growcrm] - [expenses]
        'expensesMiddlewareIndex' => \App\Http\Middleware\Expenses\Index::class,
        'expensesMiddlewareShow' => \App\Http\Middleware\Expenses\Show::class,
        'expensesMiddlewareEdit' => \App\Http\Middleware\Expenses\Edit::class,
        'expensesMiddlewareCreate' => \App\Http\Middleware\Expenses\Create::class,
        'expensesMiddlewareDownloadAttachment' => \App\Http\Middleware\Expenses\DownloadAttachment::class,
        'expensesMiddlewareDeleteAttachment' => \App\Http\Middleware\Expenses\DeleteAttachment::class,
        'expensesMiddlewareDestroy' => \App\Http\Middleware\Expenses\Destroy::class,
        'expensesMiddlewareBulkEdit' => \App\Http\Middleware\Expenses\BulkEdit::class,
        'expensesMiddlewareGeneralSingleActions' => \App\Http\Middleware\Expenses\GeneralSingleActions::class,
        'expensesMiddlewareCreateInvoice' => \App\Http\Middleware\Expenses\Createinvoice::class,

        //[growcrm] - [invoices]
        'invoicesMiddlewareIndex' => \App\Http\Middleware\Invoices\Index::class,
        'invoicesMiddlewareCreate' => \App\Http\Middleware\Invoices\Create::class,
        'invoicesMiddlewareEdit' => \App\Http\Middleware\Invoices\Edit::class,
        'invoicesMiddlewareShow' => \App\Http\Middleware\Invoices\Show::class,
        'invoicesMiddlewareDestroy' => \App\Http\Middleware\Invoices\Destroy::class,
        'invoicesMiddlewareBulkEdit' => \App\Http\Middleware\Invoices\BulkEdit::class,
        'invoicesMiddlewareGeneralSingleActions' => \App\Http\Middleware\Invoices\GeneralSingleActions::class,

        //[growcrm] - [estimates]
        'estimatesMiddlewareIndex' => \App\Http\Middleware\Estimates\Index::class,
        'estimatesMiddlewareCreate' => \App\Http\Middleware\Estimates\Create::class,
        'estimatesMiddlewareShow' => \App\Http\Middleware\Estimates\Show::class,
        'estimatesMiddlewareDestroy' => \App\Http\Middleware\Estimates\Destroy::class,
        'estimatesMiddlewareBulkEdit' => \App\Http\Middleware\Estimates\BulkEdit::class,
        'estimatesMiddlewareEdit' => \App\Http\Middleware\Estimates\Edit::class,
        'estimatesMiddlewareShowPublic' => \App\Http\Middleware\Estimates\ShowPublic::class,

        //[growcrm] - [payments]
        'paymentsMiddlewareIndex' => \App\Http\Middleware\Payments\Index::class,
        'paymentsMiddlewareShow' => \App\Http\Middleware\Payments\Show::class,
        'paymentsMiddlewareDestroy' => \App\Http\Middleware\Payments\Destroy::class,
        'paymentsMiddlewareCreate' => \App\Http\Middleware\Payments\Create::class,
        'paymentsMiddlewareBulkEdit' => \App\Http\Middleware\Payments\BulkEdit::class, //DONE
        'paymentsMiddlewareEdit' => \App\Http\Middleware\Payments\Edit::class, //DONE

        //[growcrm] - [notes]
        'notesMiddlewareIndex' => \App\Http\Middleware\Notes\Index::class,
        'notesMiddlewareCreate' => \App\Http\Middleware\Notes\Create::class,
        'notesMiddlewareEdit' => \App\Http\Middleware\Notes\Edit::class,
        'notesMiddlewareDestroy' => \App\Http\Middleware\Notes\Destroy::class,
        'notesMiddlewareShow' => \App\Http\Middleware\Notes\Show::class,

        //[growcrm] - [items]
        'itemsMiddlewareIndex' => \App\Http\Middleware\Items\Index::class,
        'itemsMiddlewareCreate' => \App\Http\Middleware\Items\Create::class,
        'itemsMiddlewareEdit' => \App\Http\Middleware\Items\Edit::class,
        'itemsMiddlewareDestroy' => \App\Http\Middleware\Items\Destroy::class,
        'itemsMiddlewareBulkEdit' => \App\Http\Middleware\Items\BulkEdit::class, //DONE

        //[growcrm] - [contacts]
        'contactsMiddlewareIndex' => \App\Http\Middleware\Contacts\Index::class,
        'contactsMiddlewareCreate' => \App\Http\Middleware\Contacts\Create::class,
        'contactsMiddlewareEdit' => \App\Http\Middleware\Contacts\Edit::class,
        'contactsMiddlewareDestroy' => \App\Http\Middleware\Contacts\Destroy::class,

        //[growcrm] - [tickets]
        'ticketsMiddlewareIndex' => \App\Http\Middleware\Tickets\Index::class,
        'ticketsMiddlewareCreate' => \App\Http\Middleware\Tickets\Create::class,
        'ticketsMiddlewareShow' => \App\Http\Middleware\Tickets\Show::class,
        'ticketsMiddlewareEdit' => \App\Http\Middleware\Tickets\Edit::class,
        'ticketsMiddlewareDestroy' => \App\Http\Middleware\Tickets\Destroy::class,
        'ticketsMiddlewareReply' => \App\Http\Middleware\Tickets\Reply::class,
        'ticketsMiddlewareDownloadAttachment' => \App\Http\Middleware\Tickets\DownloadAttachment::class,
        'ticketsMiddlewareEditReply' => \App\Http\Middleware\Tickets\EditReply::class,
        'ticketsMiddlewareBulkEdit' => \App\Http\Middleware\Tickets\BulkEdit::class, //DONE

        //[growcrm] - [leads]
        'leadsMiddlewareIndex' => \App\Http\Middleware\Leads\Index::class,
        'leadsMiddlewareCreate' => \App\Http\Middleware\Leads\Create::class,
        'leadsMiddlewareEdit' => \App\Http\Middleware\Leads\Edit::class,
        'leadsMiddlewareShow' => \App\Http\Middleware\Leads\Show::class,
        'leadsMiddlewareDestroy' => \App\Http\Middleware\Leads\Destroy::class,
        'leadsMiddlewareBulkEdit' => \App\Http\Middleware\Leads\BulkEdit::class,
        'leadsMiddlewareParticipate' => \App\Http\Middleware\Leads\Participate::class,
        'leadsMiddlewareDeleteAttachment' => \App\Http\Middleware\Leads\DeleteAttachment::class,
        'leadsMiddlewareDownloadAttachment' => \App\Http\Middleware\Leads\DownloadAttachment::class,
        'leadsMiddlewareDeleteComment' => \App\Http\Middleware\Leads\DeleteComment::class,
        'leadsMiddlewareEditDeleteChecklist' => \App\Http\Middleware\Leads\EditDeleteChecklist::class,
        'leadsMiddlewareAssign' => \App\Http\Middleware\Leads\Assign::class,
        'importLeadsMiddlewareCreate' => \App\Http\Middleware\Import\Leads\Create::class,
        'leadsMiddlewareCloning' => \App\Http\Middleware\Leads\Cloning::class,
        'leadsMiddlewareBulkAssign' => \App\Http\Middleware\Leads\BulkAssign::class,

        //[growcrm] - [tasks]
        'tasksMiddlewareIndex' => \App\Http\Middleware\Tasks\Index::class,
        'tasksMiddlewareShow' => \App\Http\Middleware\Tasks\Show::class,
        'tasksMiddlewareCreate' => \App\Http\Middleware\Tasks\Create::class,
        'tasksMiddlewareDestroy' => \App\Http\Middleware\Tasks\Destroy::class,
        'tasksMiddlewareTimer' => \App\Http\Middleware\Tasks\Timer::class,
        'tasksMiddlewareEdit' => \App\Http\Middleware\Tasks\Edit::class,
        'tasksMiddlewareParticipate' => \App\Http\Middleware\Tasks\Participate::class,
        'tasksMiddlewareDeleteAttachment' => \App\Http\Middleware\Tasks\DeleteAttachment::class,
        'tasksMiddlewareDownloadAttachment' => \App\Http\Middleware\Tasks\DownloadAttachment::class,
        'tasksMiddlewareDeleteComment' => \App\Http\Middleware\Tasks\DeleteComment::class,
        'tasksMiddlewareEditDeleteChecklist' => \App\Http\Middleware\Tasks\EditDeleteChecklist::class,
        'tasksMiddlewareAssign' => \App\Http\Middleware\Tasks\Assign::class,
        'tasksMiddlewareCloning' => \App\Http\Middleware\Tasks\Cloning::class,
        'tasksMiddlewareManageDependencies' => \App\Http\Middleware\Tasks\ManageDependencies::class,

        //[growcrm] - [files]
        'filesMiddlewareIndex' => \App\Http\Middleware\Files\Index::class,
        'filesMiddlewareCreate' => \App\Http\Middleware\Files\Create::class,
        'filesMiddlewareDownload' => \App\Http\Middleware\Files\Download::class,
        'filesMiddlewareDestroy' => \App\Http\Middleware\Files\Destroy::class,
        'filesMiddlewareEdit' => \App\Http\Middleware\Files\Edit::class,
        'filesMiddlewareMove' => \App\Http\Middleware\Files\Move::class,
        'filesMiddlewareBulkDownload' => \App\Http\Middleware\Files\BulkDownload::class,
        'manageFoldersMiddleware' => \App\Http\Middleware\Files\ManageFolders::class,
        'filesMiddlewareCopy' => \App\Http\Middleware\Files\Copy::class,

        //[growcrm] - [comments]
        'commentsMiddlewareIndex' => \App\Http\Middleware\Comments\Index::class,
        'commentsMiddlewareCreate' => \App\Http\Middleware\Comments\Create::class,
        'commentsMiddlewareDestroy' => \App\Http\Middleware\Comments\Destroy::class,

        //[growcrm] - [milestone]
        'milestonesMiddlewareIndex' => \App\Http\Middleware\Milestones\Index::class,
        'milestonesMiddlewareCreate' => \App\Http\Middleware\Milestones\Create::class,
        'milestonesMiddlewareEdit' => \App\Http\Middleware\Milestones\Edit::class,
        'milestonesMiddlewareDestroy' => \App\Http\Middleware\Milestones\Destroy::class,

        //[growcrm] - [subscription]
        'subscriptionsMiddlewareIndex' => \App\Http\Middleware\Subscriptions\Index::class,
        'subscriptionsMiddlewareCreate' => \App\Http\Middleware\Subscriptions\Create::class,
        'subscriptionsMiddlewareEdit' => \App\Http\Middleware\Subscriptions\Edit::class,
        'subscriptionsMiddlewareDestroy' => \App\Http\Middleware\Subscriptions\Destroy::class,
        'subscriptionsMiddlewareShow' => \App\Http\Middleware\Subscriptions\Show::class,
        'subscriptionsMiddlewareCancel' => \App\Http\Middleware\Subscriptions\Cancel::class,

        //[growcrm] - [milestone]
        'homeMiddlewareIndex' => \App\Http\Middleware\Home\Index::class,

        //[growcrm] - [project templates]
        'projectTemplatesGeneral' => \App\Http\Middleware\Projects\ProjectTemplatesGeneral::class,
        'projectTemplatesMiddlewareIndex' => \App\Http\Middleware\Templates\Projects\Index::class,
        'projectTemplatesMiddlewareShow' => \App\Http\Middleware\Templates\Projects\Show::class,
        'projectTemplatesMiddlewareEdit' => \App\Http\Middleware\Templates\Projects\Edit::class,
        'projectTemplatesMiddlewareCreate' => \App\Http\Middleware\Templates\Projects\Create::class,
        'projectTemplatesMiddlewareDestroy' => \App\Http\Middleware\Templates\Projects\Destroy::class,

        //[growcrm] - [customfields]
        'customfieldsMiddlewareEdit' => \App\Http\Middleware\Settings\CustomFields\Edit::class,

        //[growcrm] - [team]
        'teamMiddlewareIndex' => \App\Http\Middleware\Team\Index::class,
        'teamMiddlewareCreate' => \App\Http\Middleware\Team\Create::class,
        'teamMiddlewareEdit' => \App\Http\Middleware\Team\Edit::class,

        //[growcrm] - [proposals]
        'proposalsMiddlewareIndex' => \App\Http\Middleware\Proposals\Index::class,
        'proposalsMiddlewareShow' => \App\Http\Middleware\Proposals\Show::class,
        'proposalsMiddlewareCreate' => \App\Http\Middleware\Proposals\Create::class,
        'proposalsMiddlewareEdit' => \App\Http\Middleware\Proposals\Edit::class,
        'proposalsMiddlewareDestroy' => \App\Http\Middleware\Proposals\Destroy::class,
        'proposalsMiddlewareBulkEdit' => \App\Http\Middleware\Proposals\BulkEdit::class,
        'proposalsMiddlewareShowPublic' => \App\Http\Middleware\Proposals\ShowPublic::class,

        //[growcrm] - [contracts]
        'contractsMiddlewareIndex' => \App\Http\Middleware\Contracts\Index::class,
        'contractsMiddlewareShow' => \App\Http\Middleware\Contracts\Show::class,
        'contractsMiddlewareCreate' => \App\Http\Middleware\Contracts\Create::class,
        'contractsMiddlewareEdit' => \App\Http\Middleware\Contracts\Edit::class,
        'contractsMiddlewareDestroy' => \App\Http\Middleware\Contracts\Destroy::class,
        'contractsMiddlewareBulkEdit' => \App\Http\Middleware\Contracts\BulkEdit::class,
        'contractsMiddlewareShowPublic' => \App\Http\Middleware\Contracts\ShowPublic::class,
        'contractsMiddlewareSignClient' => \App\Http\Middleware\Contracts\SignClient::class,
        'contractsMiddlewareSignTeam' => \App\Http\Middleware\Contracts\SignTeam::class,

        //[growcrm] - [documents](proposals & contracts)
        'documentsMiddlewareEdit' => \App\Http\Middleware\Documents\Edit::class,

        //[growcrm] - [spaces]
        'spacesMiddlewareShow' => \App\Http\Middleware\Spaces\Show::class,

        //[growcrm] - [product tasks]
        'productTasksMiddlewareView' => \App\Http\Middleware\Items\TasksView::class,
        'productTasksMiddlewareEdit' => \App\Http\Middleware\Items\TasksEdit::class,

        //[growcrm] - [messages]
        'messagesMiddlewareIndex' => \App\Http\Middleware\Messages\Index::class,
        'messagesMiddlewareDestroy' => \App\Http\Middleware\Messages\Destroy::class,
        'messagesMiddlewareCreate' => \App\Http\Middleware\Messages\Create::class,

        //[growcrm] - [proposal templates]
        'proposalTemplatesMiddlewareIndex' => \App\Http\Middleware\Templates\Proposals\Index::class,
        'proposalTemplatesMiddlewareShow' => \App\Http\Middleware\Templates\Proposals\Show::class,
        'proposalTemplatesMiddlewareEdit' => \App\Http\Middleware\Templates\Proposals\Edit::class,
        'proposalTemplatesMiddlewareCreate' => \App\Http\Middleware\Templates\Proposals\Create::class,
        'proposalTemplatesMiddlewareDestroy' => \App\Http\Middleware\Templates\Proposals\Destroy::class,

        //[growcrm] - [contract templates]
        'contractTemplatesMiddlewareIndex' => \App\Http\Middleware\Templates\Contracts\Index::class,
        'contractTemplatesMiddlewareShow' => \App\Http\Middleware\Templates\Contracts\Show::class,
        'contractTemplatesMiddlewareEdit' => \App\Http\Middleware\Templates\Contracts\Edit::class,
        'contractTemplatesMiddlewareCreate' => \App\Http\Middleware\Templates\Contracts\Create::class,
        'contractTemplatesMiddlewareDestroy' => \App\Http\Middleware\Templates\Contracts\Destroy::class,

        //[growcrm] - [reports]
        'reportsMiddlewareShow' => \App\Http\Middleware\Reports\Show::class,

        //[growcrm] - [contract templates]
        'cannedMiddlewareIndex' => \App\Http\Middleware\Canned\Index::class,
        'cannedMiddlewareShow' => \App\Http\Middleware\Canned\Show::class,
        'cannedMiddlewareEdit' => \App\Http\Middleware\Canned\Edit::class,
        'cannedMiddlewareCreate' => \App\Http\Middleware\Canned\Create::class,
        'cannedMiddlewareDestroy' => \App\Http\Middleware\Canned\Destroy::class,

        //[growcrm] - [reports]
        'searchMiddlewareIndex' => \App\Http\Middleware\Search\Index::class,
    ];
}
