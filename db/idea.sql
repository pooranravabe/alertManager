-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2016 at 12:52 PM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `idea`
--

-- --------------------------------------------------------

--
-- Table structure for table `idea_client_group`
--

CREATE TABLE `idea_client_group` (
  `id` int(11) NOT NULL,
  `group` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idea_client_group_user`
--

CREATE TABLE `idea_client_group_user` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idea_idea`
--

CREATE TABLE `idea_idea` (
  `id` int(10) UNSIGNED NOT NULL,
  `idea` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idea_idea_project`
--

CREATE TABLE `idea_idea_project` (
  `id` int(11) NOT NULL,
  `idea_id` int(11) UNSIGNED NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_projects`
--

CREATE TABLE `idea_projects` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `project` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `background` text COLLATE utf8_unicode_ci,
  `deadline_date` date DEFAULT NULL,
  `deadline_time` time DEFAULT NULL,
  `supporting_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idea_project_clientgroups`
--

CREATE TABLE `idea_project_clientgroups` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_user_idea`
--

CREATE TABLE `idea_user_idea` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `idea_id` int(11) UNSIGNED NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_user_project`
--

CREATE TABLE `idea_user_project` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_user_workflow_relation`
--

CREATE TABLE `idea_user_workflow_relation` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `userrole` enum('admin','creator','contributor') NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow`
--

CREATE TABLE `idea_workflow` (
  `id` int(11) NOT NULL,
  `workflow` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type_id` int(11) NOT NULL,
  `parentid` int(11) NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `last_view` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_archive_content`
--

CREATE TABLE `idea_workflow_archive_content` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `archived_date` date NOT NULL DEFAULT '2016-01-27',
  `delete_archived_date` date NOT NULL DEFAULT '2016-01-27',
  `archived_day` int(10) NOT NULL DEFAULT '30',
  `delete_viewed_day` int(10) NOT NULL DEFAULT '30',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_attachments`
--

CREATE TABLE `idea_workflow_attachments` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `attachment` varchar(355) NOT NULL,
  `attachments_type` varchar(255) NOT NULL,
  `attachments_size` varchar(255) NOT NULL,
  `attachments_resolution` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `alt` varchar(50) NOT NULL,
  `camefrom` enum('direct','search engine') NOT NULL,
  `source` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_audios`
--

CREATE TABLE `idea_workflow_audios` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `audio` varchar(355) NOT NULL,
  `audios_type` varchar(255) NOT NULL,
  `audios_size` varchar(255) NOT NULL,
  `audios_resolution` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `alt` varchar(50) NOT NULL,
  `camefrom` enum('direct','search engine') NOT NULL,
  `source` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_comments`
--

CREATE TABLE `idea_workflow_comments` (
  `id` int(11) NOT NULL,
  `userid` int(11) UNSIGNED NOT NULL,
  `workflowid` int(11) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `comment` varchar(355) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_images`
--

CREATE TABLE `idea_workflow_images` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `image` varchar(355) NOT NULL,
  `images_type` varchar(255) NOT NULL,
  `images_size` varchar(255) NOT NULL,
  `images_resolution` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `alt` varchar(50) NOT NULL,
  `camefrom` enum('direct','search engine') NOT NULL,
  `source` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_othermedias`
--

CREATE TABLE `idea_workflow_othermedias` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `othermedia` varchar(355) NOT NULL,
  `othermedias_type` varchar(255) NOT NULL,
  `othermedias_size` varchar(255) NOT NULL,
  `othermedias_resolution` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `alt` varchar(50) NOT NULL,
  `camefrom` enum('direct','search engine') NOT NULL,
  `source` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_pdf`
--

CREATE TABLE `idea_workflow_pdf` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `pdf` varchar(355) NOT NULL,
  `pdf_type` varchar(255) NOT NULL,
  `pdf_size` varchar(255) NOT NULL,
  `pdf_resolution` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `alt` varchar(50) NOT NULL,
  `camefrom` enum('direct','search engine') NOT NULL,
  `source` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_ppt`
--

CREATE TABLE `idea_workflow_ppt` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `ppt` varchar(355) NOT NULL,
  `ppt_type` varchar(255) NOT NULL,
  `ppt_size` varchar(255) NOT NULL,
  `ppt_resolution` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `alt` varchar(50) NOT NULL,
  `camefrom` enum('direct','search engine') NOT NULL,
  `source` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_taggs`
--

CREATE TABLE `idea_workflow_taggs` (
  `id` int(11) NOT NULL,
  `userid` int(11) UNSIGNED NOT NULL,
  `workflowid` int(11) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `mediaid` int(11) NOT NULL,
  `tag` varchar(355) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_types`
--

CREATE TABLE `idea_workflow_types` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `wk_order` int(11) NOT NULL,
  `wk_title` enum('0','1') NOT NULL DEFAULT '1',
  `wk_description` enum('0','1') NOT NULL,
  `wk_attachments` enum('0','1') NOT NULL,
  `wk_upload_image` enum('0','1') NOT NULL,
  `wk_upload_vedio` enum('0','1') NOT NULL,
  `wk_upload_audio` enum('0','1') NOT NULL,
  `wk_upload_pdf` enum('0','1') NOT NULL,
  `wk_upload_ppt` enum('0','1') NOT NULL,
  `wk_preview_button` enum('0','1') NOT NULL,
  `wk_save_a_draft` enum('0','1') NOT NULL,
  `wk_cancel_button` enum('0','1') NOT NULL,
  `wk_done_button` enum('0','1') NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idea_workflow_videos`
--

CREATE TABLE `idea_workflow_videos` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `workflowid` int(11) NOT NULL,
  `video` varchar(355) NOT NULL,
  `videos_type` varchar(255) NOT NULL,
  `videos_size` varchar(255) NOT NULL,
  `videos_resolution` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `alt` varchar(50) NOT NULL,
  `camefrom` enum('direct','search engine') NOT NULL,
  `source` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organization_users`
--

CREATE TABLE `organization_users` (
  `id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissions_sets`
--

CREATE TABLE `permissions_sets` (
  `id` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `pread` enum('0','1') NOT NULL DEFAULT '0',
  `pwrite` enum('0','1') NOT NULL DEFAULT '0',
  `pupdate` enum('0','1') NOT NULL DEFAULT '0',
  `pdelete` enum('0','1') NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permission_auth`
--

CREATE TABLE `permission_auth` (
  `id` int(11) NOT NULL,
  `userid` int(11) UNSIGNED NOT NULL,
  `cid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permission_roles`
--

CREATE TABLE `permission_roles` (
  `id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `role_categories`
--

CREATE TABLE `role_categories` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `role_modules`
--

CREATE TABLE `role_modules` (
  `id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `p_id` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login_time` tinyint(1) NOT NULL DEFAULT '1',
  `last_known_location` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `idea_client_group`
--
ALTER TABLE `idea_client_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `idea_client_group_user`
--
ALTER TABLE `idea_client_group_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `idea_idea`
--
ALTER TABLE `idea_idea`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `idea_idea_project`
--
ALTER TABLE `idea_idea_project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idea_id` (`idea_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `idea_projects`
--
ALTER TABLE `idea_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orgid` (`orgid`);

--
-- Indexes for table `idea_project_clientgroups`
--
ALTER TABLE `idea_project_clientgroups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `idea_user_idea`
--
ALTER TABLE `idea_user_idea`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idea_id` (`idea_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `idea_user_project`
--
ALTER TABLE `idea_user_project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `idea_user_workflow_relation`
--
ALTER TABLE `idea_user_workflow_relation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`);

--
-- Indexes for table `idea_workflow`
--
ALTER TABLE `idea_workflow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `idea_workflow_archive_content`
--
ALTER TABLE `idea_workflow_archive_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `idea_workflow_attachments`
--
ALTER TABLE `idea_workflow_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`);

--
-- Indexes for table `idea_workflow_audios`
--
ALTER TABLE `idea_workflow_audios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`);

--
-- Indexes for table `idea_workflow_comments`
--
ALTER TABLE `idea_workflow_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `idea_workflow_images`
--
ALTER TABLE `idea_workflow_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`);

--
-- Indexes for table `idea_workflow_othermedias`
--
ALTER TABLE `idea_workflow_othermedias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`);

--
-- Indexes for table `idea_workflow_pdf`
--
ALTER TABLE `idea_workflow_pdf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`);

--
-- Indexes for table `idea_workflow_ppt`
--
ALTER TABLE `idea_workflow_ppt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`);

--
-- Indexes for table `idea_workflow_taggs`
--
ALTER TABLE `idea_workflow_taggs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `idea_workflow_types`
--
ALTER TABLE `idea_workflow_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `idea_workflow_videos`
--
ALTER TABLE `idea_workflow_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflowid` (`workflowid`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_users`
--
ALTER TABLE `organization_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `permissions_sets`
--
ALTER TABLE `permissions_sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cid` (`cid`),
  ADD KEY `mid` (`mid`),
  ADD KEY `rid` (`rid`);

--
-- Indexes for table `permission_auth`
--
ALTER TABLE `permission_auth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `cid` (`cid`),
  ADD KEY `mid` (`mid`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `permission_roles`
--
ALTER TABLE `permission_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_categories`
--
ALTER TABLE `role_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_modules`
--
ALTER TABLE `role_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cid` (`cid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_activation_code_index` (`activation_code`),
  ADD KEY `users_reset_password_code_index` (`reset_password_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `idea_client_group`
--
ALTER TABLE `idea_client_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `idea_client_group_user`
--
ALTER TABLE `idea_client_group_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `idea_idea`
--
ALTER TABLE `idea_idea`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `idea_idea_project`
--
ALTER TABLE `idea_idea_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `idea_projects`
--
ALTER TABLE `idea_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `idea_project_clientgroups`
--
ALTER TABLE `idea_project_clientgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `idea_user_idea`
--
ALTER TABLE `idea_user_idea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `idea_user_project`
--
ALTER TABLE `idea_user_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `idea_user_workflow_relation`
--
ALTER TABLE `idea_user_workflow_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `idea_workflow`
--
ALTER TABLE `idea_workflow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `idea_workflow_archive_content`
--
ALTER TABLE `idea_workflow_archive_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `idea_workflow_attachments`
--
ALTER TABLE `idea_workflow_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `idea_workflow_audios`
--
ALTER TABLE `idea_workflow_audios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `idea_workflow_comments`
--
ALTER TABLE `idea_workflow_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `idea_workflow_images`
--
ALTER TABLE `idea_workflow_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `idea_workflow_othermedias`
--
ALTER TABLE `idea_workflow_othermedias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `idea_workflow_pdf`
--
ALTER TABLE `idea_workflow_pdf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `idea_workflow_ppt`
--
ALTER TABLE `idea_workflow_ppt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `idea_workflow_taggs`
--
ALTER TABLE `idea_workflow_taggs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `idea_workflow_types`
--
ALTER TABLE `idea_workflow_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `idea_workflow_videos`
--
ALTER TABLE `idea_workflow_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;
--
-- AUTO_INCREMENT for table `organization_users`
--
ALTER TABLE `organization_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT for table `permissions_sets`
--
ALTER TABLE `permissions_sets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=323;
--
-- AUTO_INCREMENT for table `permission_auth`
--
ALTER TABLE `permission_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `permission_roles`
--
ALTER TABLE `permission_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `role_categories`
--
ALTER TABLE `role_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `role_modules`
--
ALTER TABLE `role_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `idea_client_group_user`
--
ALTER TABLE `idea_client_group_user`
  ADD CONSTRAINT `idea_client_group_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `idea_client_group` (`id`),
  ADD CONSTRAINT `idea_client_group_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `idea_idea_project`
--
ALTER TABLE `idea_idea_project`
  ADD CONSTRAINT `idea_idea_project_ibfk_1` FOREIGN KEY (`idea_id`) REFERENCES `idea_idea` (`id`),
  ADD CONSTRAINT `idea_idea_project_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `idea_projects` (`id`),
  ADD CONSTRAINT `idea_idea_project_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `idea_projects`
--
ALTER TABLE `idea_projects`
  ADD CONSTRAINT `idea_projects_ibfk_1` FOREIGN KEY (`orgid`) REFERENCES `organization` (`id`);

--
-- Constraints for table `idea_project_clientgroups`
--
ALTER TABLE `idea_project_clientgroups`
  ADD CONSTRAINT `idea_project_clientgroups_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `idea_projects` (`id`),
  ADD CONSTRAINT `idea_project_clientgroups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `idea_client_group` (`id`),
  ADD CONSTRAINT `idea_project_clientgroups_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `idea_user_idea`
--
ALTER TABLE `idea_user_idea`
  ADD CONSTRAINT `idea_user_idea_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `idea_user_idea_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `idea_user_idea_ibfk_3` FOREIGN KEY (`idea_id`) REFERENCES `idea_idea` (`id`),
  ADD CONSTRAINT `idea_user_idea_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `idea_user_project`
--
ALTER TABLE `idea_user_project`
  ADD CONSTRAINT `idea_user_project_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `idea_user_project_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `idea_projects` (`id`),
  ADD CONSTRAINT `idea_user_project_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `idea_user_workflow_relation`
--
ALTER TABLE `idea_user_workflow_relation`
  ADD CONSTRAINT `idea_user_workflow_relation_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`);

--
-- Constraints for table `idea_workflow`
--
ALTER TABLE `idea_workflow`
  ADD CONSTRAINT `idea_workflow_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `idea_workflow_types` (`id`);

--
-- Constraints for table `idea_workflow_attachments`
--
ALTER TABLE `idea_workflow_attachments`
  ADD CONSTRAINT `idea_Workflow_attachments_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`);

--
-- Constraints for table `idea_workflow_audios`
--
ALTER TABLE `idea_workflow_audios`
  ADD CONSTRAINT `idea_Workflow_audios_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`);

--
-- Constraints for table `idea_workflow_comments`
--
ALTER TABLE `idea_workflow_comments`
  ADD CONSTRAINT `idea_workflow_comments_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`),
  ADD CONSTRAINT `idea_workflow_comments_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `idea_workflow_images`
--
ALTER TABLE `idea_workflow_images`
  ADD CONSTRAINT `idea_Workflow_images_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`);

--
-- Constraints for table `idea_workflow_othermedias`
--
ALTER TABLE `idea_workflow_othermedias`
  ADD CONSTRAINT `idea_Workflow_othermedias_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`);

--
-- Constraints for table `idea_workflow_pdf`
--
ALTER TABLE `idea_workflow_pdf`
  ADD CONSTRAINT `idea_Workflow_pdf_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`);

--
-- Constraints for table `idea_workflow_ppt`
--
ALTER TABLE `idea_workflow_ppt`
  ADD CONSTRAINT `idea_Workflow_ppt_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`);

--
-- Constraints for table `idea_workflow_taggs`
--
ALTER TABLE `idea_workflow_taggs`
  ADD CONSTRAINT `idea_workflow_taggs_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`),
  ADD CONSTRAINT `idea_workflow_taggs_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `idea_workflow_videos`
--
ALTER TABLE `idea_workflow_videos`
  ADD CONSTRAINT `idea_Workflow_videos_ibfk_1` FOREIGN KEY (`workflowid`) REFERENCES `idea_workflow` (`id`);

--
-- Constraints for table `organization_users`
--
ALTER TABLE `organization_users`
  ADD CONSTRAINT `organization_users_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organization` (`id`);

--
-- Constraints for table `permissions_sets`
--
ALTER TABLE `permissions_sets`
  ADD CONSTRAINT `permissions_sets_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `role_categories` (`id`),
  ADD CONSTRAINT `permissions_sets_ibfk_2` FOREIGN KEY (`mid`) REFERENCES `role_modules` (`id`),
  ADD CONSTRAINT `permissions_sets_ibfk_3` FOREIGN KEY (`rid`) REFERENCES `role_categories` (`id`);

--
-- Constraints for table `permission_auth`
--
ALTER TABLE `permission_auth`
  ADD CONSTRAINT `permission_auth_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `permission_auth_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `role_categories` (`id`),
  ADD CONSTRAINT `permission_auth_ibfk_3` FOREIGN KEY (`mid`) REFERENCES `role_modules` (`id`),
  ADD CONSTRAINT `permission_auth_ibfk_4` FOREIGN KEY (`pid`) REFERENCES `permission_roles` (`id`);

--
-- Constraints for table `role_modules`
--
ALTER TABLE `role_modules`
  ADD CONSTRAINT `role_modules_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `role_categories` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
