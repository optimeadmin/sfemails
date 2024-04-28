import React from 'react'
import { useUrl } from '../../contexts/UrlContext'
import { PreviewInModal } from '../preview/Preview'
import { ButtonModal } from '../ui/ButtonModal.tsx'

type Props = {
  uuid: string | null
  title: string,
}

export function PreviewEmailTemplateAction ({ uuid, title }: Props) {
  const { apiUrl } = useUrl()
  const previewUrl = `${apiUrl}/preview/template/${uuid}`
  const disabled = !Boolean(uuid)

  return (
    <ButtonModal
      modalProps={{ size: 'lg' }}
      modalContent={<PreviewInModal url={previewUrl}/>}
      disabled={disabled}
      className="text-nowrap"
      size="sm"
      variant="outline-secondary"
    >
      {title}
    </ButtonModal>
  )
}