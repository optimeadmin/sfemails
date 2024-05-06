import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { EmailTemplate, ExistentEmailTemplate } from '../types'
import { addServerError } from '../utils/errors'
import { AxiosError } from 'axios'
import { useForm, UseFormSetError } from 'react-hook-form'
import { useNavigate } from 'react-router-dom'
import { toast } from 'react-toastify'
import { getEmailTemplate, getEmailTemplateVars, getTemplates, saveEmailTemplate } from '../api/templates.ts'

export function useSaveTemplate (addError?: UseFormSetError<EmailTemplate>) {
  const queryClient = useQueryClient()
  const { mutateAsync, isPending } = useMutation({
    async mutationFn (emailTemplate: EmailTemplate) {
      await saveEmailTemplate(emailTemplate)
    },
    async onSuccess () {
      return queryClient.invalidateQueries({ queryKey: ['templates'] })
    },
    onError (error: AxiosError) {
      addServerError(error, addError)
    }
  })

  return {
    save: mutateAsync,
    isPending,
  }
}

export function useGetTemplates () {
  const { isLoading, data: templates } = useQuery({
    queryKey: ['templates'],
    queryFn: ({ signal }) => getTemplates(signal),
    staleTime: 1000 * 60 * 5,
  })

  return {
    isLoading,
    templates,
  }
}

export function useGetTemplateByUuid (uuid: string) {
  const { isLoading, data: template } = useQuery({
    queryKey: ['templates', 'item', uuid],
    queryFn: ({ signal }) => getEmailTemplate(uuid, signal),
  })

  return {
    isLoading,
    template,
  }
}

export function useTemplateForm (emailTemplate?: ExistentEmailTemplate) {
  const navigate = useNavigate()
  const form = useForm<EmailTemplate>({ values: emailTemplate })
  const isEdit = !!emailTemplate

  const { save, isPending } = useSaveTemplate(form.setError)

  async function sendForm (data: EmailTemplate) {
    await save(data)
    if (!isEdit) {
      navigate('/templates')
    }
    toast.success(isEdit ? 'Email Template saved successfully!' : 'Email Template created successfully!', {
      autoClose: isEdit ? 1000 : 3000
    })
  }

  return {
    isPending,
    form,
    sendForm,
  }
}

export function useGetTemplateVarsByUuid (uuid: string) {
  const { isLoading, data: vars } = useQuery({
    queryKey: ['templates', 'item', 'vars', uuid],
    queryFn: ({ signal }) => getEmailTemplateVars(uuid, signal),
  })

  return {
    isLoading,
    vars,
  }
}