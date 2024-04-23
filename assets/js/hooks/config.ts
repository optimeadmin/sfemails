import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { Config, ExistentConfig, Layout } from '../types'
import { getLayout, saveLayout } from '../api/layouts'
import { addServerError } from '../utils/errors'
import { AxiosError } from 'axios'
import { useForm, UseFormSetError } from 'react-hook-form'
import { getConfigs, saveConfig } from '../api/configs.ts'
import { useGetLayouts } from './layout.ts'
import { useNavigate } from 'react-router-dom'
import { useEffect } from 'react'
import { toast } from 'react-toastify'

export function useSaveConfig (addError?: UseFormSetError<Config>) {
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

export function useGetConfigs () {
  const { isLoading, data: configs } = useQuery({
    queryKey: ['configs'],
    queryFn: ({ signal }) => getConfigs(signal),
  })

  return {
    isLoading,
    configs,
  }
}

export function useGetLayoutByUuid (uuid: string) {
  const { isLoading, data: layout } = useQuery({
    queryKey: ['layouts', 'item', uuid],
    queryFn: ({ signal }) => getLayout(uuid, signal),
  })

  return {
    isLoading,
    layout,
  }
}

export function useConfigForm (config?: ExistentConfig) {
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