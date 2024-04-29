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
  locale: string,
  vars: string,
  emails: string[]
}

export type EmailStatus = 'send' | 'no_template' | 'pending' | 'error'
export type EmailLogVars = Object<string, any>

export type EmailLog = {
  uuid: string,
  locale: string,
  configCode: string,
  emailSubject: string | null,
  status: EmailStatus,
  statusTitle: string,
  recipient: string,
  sessionUser: string,
  sendAt: string,
  error: string | null,
  vars: EmailLogVars,
  resend: boolean,
  canResend: boolean,
}