--
-- Table structure for table `contact_form_submit`
--

CREATE TABLE `contact_form_submit` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` int(10) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `contact_form_submit`
--
ALTER TABLE `contact_form_submit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `contact_form_submit`
--
ALTER TABLE `contact_form_submit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;