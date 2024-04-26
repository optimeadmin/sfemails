export type EntityDates = {
  createdAt: string,
  updatedAt: string,
}

type TypeWithDates = {
  dates: EntityDates
}

export type Layout = {
  description?: string,
  content?: Record<string, string | null>,
}

export type ExistentLayout = {
  id: number,
  uuid: string,
} & Layout & TypeWithDates

export type Config = {
  code: string,
  description: string,
  layoutUuid: string,
  layoutTitle?: string,
  editable: boolean,
  target: string,
}

export type ExistentConfig = {
  uuid: string,
} & Config & TypeWithDates

export type EmailTemplate = {
  appId: number,
  layoutUuid?: string,
  layoutTitle?: string,
  configUuid: string,
  subject?: Record<string, string | null>,
  content?: Record<string, string | null>,
  active: boolean,
}

export type ExistentEmailTemplate = {
  uuid: string,
  appTitle: string,
  configCode: string,
} & EmailTemplate & TypeWithDates

export type EmailApp = {
  id: number,
  title: string,
}

export type EmailTemplateVars = {
  yaml: string
}

export type EmailTestValues = {
  vars: string,
  emails: string[]
}