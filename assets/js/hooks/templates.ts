import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { Config, ExistentConfig } from '../types'
import { addServerError } from '../utils/errors'
import { AxiosError } from 'axios'
import { useForm, UseFormSetError } from 'react-hook-form'
import { getConfig, saveConfig } from '../api/configs.ts'
import { useGetLayouts } from './layout.ts'
import { useNavigate } from 'react-router-dom'
import { useEffect } from 'react'
import { toast } from 'react-toastify'
import { getTemplates } from '../api/templates.ts'

export function useSaveTemplate (addError?: UseFormSetError<Config>) {
  const queryClient = useQueryClient()
  const { mutateAsync, isPending } = useMutation({
    async mutationFn (config: Config) {
      await saveConfig(config)
    },
    async onSuccess () {
      return queryClient.invalidateQueries({ queryKey: ['configs'] })
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
    queryFn: ({ signal }) => getConfig(uuid, signal),
  })

  return {
    isLoading,
    template,
  }
}

export function useTemplateForm (config?: ExistentConfig) {
  const { layouts, isLoading: isLoadingLayouts } = useGetLayouts()
  const navigate = useNavigate()
  const form = useForm<Config>({ values: config })
  const isEdit = !!config

  useEffect(() => {
    if (!layouts || layouts.length === 0) return
    if (config) return

    form.setValue('layoutUuid', layouts[0].uuid)
  }, [form, layouts, config])

  const { save, isPending } = useSaveConfig(form.setError)

  async function sendForm (data: Config) {
    try {
      await save(data)
      navigate('/')
      toast.success(isEdit ? 'Config saved successfully!' : 'Config created successfully!')
    } catch (e) {
      toast.error('Ups, an error has occurred!')
    }
  }

  return {
    isLoadingLayouts,
    isPending,
    layouts,
    form,
    sendForm,
  }
}