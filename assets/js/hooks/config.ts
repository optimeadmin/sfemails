import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { Config, ExistentConfig } from '../types'
import { addServerError } from '../utils/errors'
import { AxiosError } from 'axios'
import { useForm, UseFormSetError } from 'react-hook-form'
import { getConfig, getConfigs, saveConfig } from '../api/configs.ts'
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
    staleTime: 1000 * 60 * 5,
  })

  return {
    isLoading,
    configs,
  }
}

export function useGetConfigByUuid (uuid: string) {
  const { isLoading, data: config } = useQuery({
    queryKey: ['configs', 'item', uuid],
    queryFn: ({ signal }) => getConfig(uuid, signal),
  })

  return {
    isLoading,
    config,
  }
}

export function useConfigForm (config?: ExistentConfig) {
  const { layouts } = useGetLayouts()
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
    await save(data)
    !isEdit && navigate('/')
    toast.success(isEdit ? 'Config saved successfully!' : 'Config created successfully!', {
      autoClose: isEdit ? 1000 : 3000
    })
  }

  return {
    isPending,
    form,
    sendForm,
  }
}