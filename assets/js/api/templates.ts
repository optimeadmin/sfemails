import { EmailTemplate, EmailTemplateVars, EmailTestValues, ExistentEmailTemplate } from '../types'
import { axiosApi } from './axiosInstances'

export async function getTemplates (signal: AbortSignal): Promise<ExistentEmailTemplate[]> {
  const { data } = await axiosApi.get(`/templates`, { signal })

  return data as ExistentEmailTemplate[]
}

export async function getEmailTemplate (uuid: string, signal: AbortSignal): Promise<ExistentEmailTemplate> {
  const { data } = await axiosApi.get(`/templates/${uuid}`, { signal })

  return data as ExistentEmailTemplate
}

export async function getEmailTemplateVars (uuid: string, signal: AbortSignal): Promise<EmailTemplateVars> {
  const { data } = await axiosApi.get(`/templates/vars/${uuid}`, { signal })

  return data as EmailTemplateVars
}

export async function saveEmailTemplate (emailTemplate: EmailTemplate | ExistentEmailTemplate): Promise<void> {
  if ('uuid' in emailTemplate && emailTemplate.uuid) {
    await axiosApi.patch(`/templates/${emailTemplate.uuid}`, emailTemplate)
  } else {
    await axiosApi.post(`/templates`, emailTemplate)
  }
}

export async function sendEmailTest (uuid: string, testData: EmailTestValues): Promise<number> {
  const { status } = await axiosApi.post(`/templates/test/${uuid}`, testData)

  return status
}