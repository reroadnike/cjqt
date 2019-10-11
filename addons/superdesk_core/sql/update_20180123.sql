




## 已更新到服务器



ALTER TABLE `ims_superdesk_core_build`
  DROP `createtime_`,
  DROP `updatetime`,
  DROP `enabled`;


ALTER TABLE `ims_superdesk_core_dictionary_group`
  DROP `createtime_`,
  DROP `updatetime`,
  DROP `enabled`;

ALTER TABLE `ims_superdesk_core_dictionary_item`
  DROP `createtime_`,
  DROP `updatetime`,
  DROP `enabled`;

ALTER TABLE `ims_superdesk_core_organization`
  DROP `createtime_`,
  DROP `updatetime`,
  DROP `enabled`;

ALTER TABLE `ims_superdesk_core_provincecity`
  DROP `createtime_`,
  DROP `updatetime`,
  DROP `enabled`;

ALTER TABLE `ims_superdesk_core_virtualarchitecture`
  DROP `createtime_`,
  DROP `updatetime`,
  DROP `enabled`;